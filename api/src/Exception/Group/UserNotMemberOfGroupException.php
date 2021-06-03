<?php

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserNotMemberOfGroupException extends ConflictHttpException
{
    private const NOT_MEMBER = 'The user is not a member of the group.';

    public function __construct()
    {
        parent::__construct(self::NOT_MEMBER);
    }
}
