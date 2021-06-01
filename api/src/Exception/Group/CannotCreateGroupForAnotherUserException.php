<?php

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateGroupForAnotherUserException extends AccessDeniedHttpException
{
    private const GROUP_FOR_ANOTHER_USER = 'You can\'t create groups for another user';

    public function __construct()
    {
        parent::__construct(self::GROUP_FOR_ANOTHER_USER);
    }
}
