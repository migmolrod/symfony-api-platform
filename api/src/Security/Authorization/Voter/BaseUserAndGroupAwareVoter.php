<?php

namespace App\Security\Authorization\Voter;

use App\Entity\Category;
use App\Entity\Movement;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class BaseUserAndGroupAwareVoter extends Voter
{
    abstract protected function supports(string $attribute, $subject): bool;

    abstract protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool;

    /**
     * @param Category|Movement|null $subject
     */
    protected function checkUserAndGroup(string $attribute, $subject, TokenInterface $token, string $createAttribute): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        if ($createAttribute === $attribute) {
            return true;
        }
        if ((null !== $group = $subject->getGroup()) && in_array($attribute, $this->supportedAttributes(), true)) {
            return $user->isMemberOfGroup($group);
        }
        if (in_array($attribute, $this->supportedAttributes(), true)) {
            return $subject->isOwnedBy($user);
        }

        return false;
    }

    abstract protected function supportedAttributes(): array;
}
