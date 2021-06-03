<?php

namespace App\Exception\GroupRequest;

use function sprintf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupRequestNotFoundException extends NotFoundHttpException
{
    private const NOT_FOUND = 'Group request for group %s and user %s and token %s not found or already accepted.';

    public static function fromGroupAndUserAndToken(string $groupId, string $userId, string $token): self
    {
        throw new self(sprintf(self::NOT_FOUND, $groupId, $userId, $token));
    }
}
