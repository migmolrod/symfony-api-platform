<?php

namespace App\Exception\Group;

use function sprintf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupNotFoundException extends NotFoundHttpException
{
    private const ID_NOT_FOUND = 'Group with id %s not found';

    public static function fromId(string $groupId): self
    {
        throw new self(sprintf(self::ID_NOT_FOUND, $groupId));
    }
}
