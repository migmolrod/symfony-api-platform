<?php

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CannotRemoveOwnerException extends ConflictHttpException
{
    private const CANNOT_REMOVE_OWNER = 'The owner of a group can\'t be removed from such group. Try deleting the group instead.';

    public function __construct()
    {
        parent::__construct(self::CANNOT_REMOVE_OWNER);
    }
}
