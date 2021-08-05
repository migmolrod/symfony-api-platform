<?php

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateMovementForAnotherGroupException extends AccessDeniedHttpException
{
    private const MOVEMENT_FOR_ANOTHER_GROUP = 'You can\'t create movements for a group you\'re not member of.';

    public function __construct()
    {
        parent::__construct(self::MOVEMENT_FOR_ANOTHER_GROUP);
    }
}
