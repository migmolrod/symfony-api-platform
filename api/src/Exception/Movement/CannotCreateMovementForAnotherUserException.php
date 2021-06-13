<?php

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateMovementForAnotherUserException extends AccessDeniedHttpException
{
    private const MOVEMENT_FOR_ANOTHER_USER = 'You can\'t create movements for another user.';

    public function __construct()
    {
        parent::__construct(self::MOVEMENT_FOR_ANOTHER_USER);
    }
}
