<?php

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotRemoveAnotherUserIfNotOwnerException extends AccessDeniedHttpException
{
    private const REMOVE_USER_IF_NOT_OWNER = 'You can\'t remove users from groups you don\'t own.';

    public function __construct()
    {
        parent::__construct(self::REMOVE_USER_IF_NOT_OWNER);
    }
}
