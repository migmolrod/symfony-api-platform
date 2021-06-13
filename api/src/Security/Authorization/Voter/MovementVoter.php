<?php

namespace App\Security\Authorization\Voter;

use App\Entity\Movement;
use function in_array;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MovementVoter extends BaseUserAndGroupAwareVoter
{
    public const MOVEMENT_CREATE = 'MOVEMENT_CREATE';
    public const MOVEMENT_READ = 'MOVEMENT_READ';
    public const MOVEMENT_UPDATE = 'MOVEMENT_UPDATE';
    public const MOVEMENT_DELETE = 'MOVEMENT_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->supportedAttributes(), true);
    }

    protected function supportedAttributes(): array
    {
        return [
            self::MOVEMENT_CREATE,
            self::MOVEMENT_READ,
            self::MOVEMENT_UPDATE,
            self::MOVEMENT_DELETE,
        ];
    }

    /**
     * @param Movement|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->checkUserAndGroup($attribute, $subject, $token, self::MOVEMENT_CREATE);
    }
}
