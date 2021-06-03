<?php

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class NotOwnerOfGroupException extends AccessDeniedHttpException
{
    private const NOT_OWNER = 'You are not the owner of this group.';

    public function __construct()
    {
        parent::__construct(self::NOT_OWNER);
    }
}
