<?php

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserAlreadyMemberOfGroupException extends ConflictHttpException
{
    private const ALREADY_MEMBER = 'The user is already member of the group.';

    public function __construct()
    {
        parent::__construct(self::ALREADY_MEMBER);
    }
}
