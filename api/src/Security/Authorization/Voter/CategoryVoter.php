<?php

namespace App\Security\Authorization\Voter;

use App\Entity\Category;
use function in_array;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CategoryVoter extends BaseUserAndGroupAwareVoter
{
    public const CATEGORY_CREATE = 'CATEGORY_CREATE';
    public const CATEGORY_READ = 'CATEGORY_READ';
    public const CATEGORY_UPDATE = 'CATEGORY_UPDATE';
    public const CATEGORY_DELETE = 'CATEGORY_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->supportedAttributes(), true);
    }

    protected function supportedAttributes(): array
    {
        return [
            self::CATEGORY_CREATE,
            self::CATEGORY_READ,
            self::CATEGORY_UPDATE,
            self::CATEGORY_DELETE,
        ];
    }

    /**
     * @param Category|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->checkUserAndGroup($attribute, $subject, $token, self::CATEGORY_CREATE);
    }
}
