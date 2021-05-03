<?php

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PasswordException extends BadRequestHttpException
{
    private const MESSAGE = 'Password must be at least %s characters long';

    public static function invalidLength(int $minimumLength): PasswordException
    {
        throw new self(\sprintf(self::MESSAGE, $minimumLength));
    }
}
