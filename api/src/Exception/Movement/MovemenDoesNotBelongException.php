<?php

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MovemenDoesNotBelongException extends AccessDeniedHttpException
{
    private const DOES_NOT_BELONG_TO_USER = 'Movement with id %s does not belong to user with id %s';
    private const DOES_NOT_BELONG_TO_GROUP = 'Movement with id %s does not belong to group with id %s';

    public static function doesNotBelongToUser(string $movementId, string $userId): self
    {
        throw new self(\sprintf(self::DOES_NOT_BELONG_TO_USER, $movementId, $userId));
    }

    public static function doesNotBelongToGroup(string $movementId, string $groupId): self
    {
        throw new self(\sprintf(self::DOES_NOT_BELONG_TO_GROUP, $movementId, $groupId));
    }
}
