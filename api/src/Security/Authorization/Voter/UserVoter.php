<?php

namespace App\Security\Authorization\Voter;

use App\Entity\User;
use function in_array;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const USER_READ = 'USER_READ';
    public const USER_UPDATE = 'USER_UPDATE';
    public const USER_DELETE = 'USER_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->supportedAttributes(), true);
    }

    private function supportedAttributes(): array
    {
        return [
            self::USER_READ,
            self::USER_UPDATE,
            self::USER_DELETE,
        ];
    }

    /**
     * @param User|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (in_array($attribute, $this->supportedAttributes(), true)) {
            return $subject->equals($token->getUser());
        }

        return false;
    }
}
