<?php

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function sprintf;

class PasswordException extends BadRequestHttpException
{
    private const MIN_LENGTH = 'Password must be at least %s characters long';
    private const OLD_PASSWORD_DOES_NOT_MATCH = 'Old password does not match';

    public static function invalidLength(int $minimumLength): self
    {
        throw new self(sprintf(self::MIN_LENGTH, $minimumLength));
    }

    public static function oldPasswordDoesNotMatch(): self
    {
        throw new self(self::OLD_PASSWORD_DOES_NOT_MATCH);
    }
}
