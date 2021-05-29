<?php

namespace App\Exception\User;

use function sprintf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const ID_NOT_FOUND = 'User with id %s not found';
    private const EMAIL_NOT_FOUND = 'User with email %s not found';
    private const ID_AND_TOKEN_NOT_FOUND = 'User with id %s and token %s not found or already active';
    private const ID_AND_RESET_PASSWORD_TOKEN_NOT_FOUND = 'User with id %s and reset password token %s not found';

    public static function fromId(string $userId): self
    {
        throw new self(sprintf(self::ID_NOT_FOUND, $userId));
    }

    public static function fromEmail(string $email): self
    {
        throw new self(sprintf(self::EMAIL_NOT_FOUND, $email));
    }

    public static function fromIdAndToken(string $id, string $token): self
    {
        throw new self(sprintf(self::ID_AND_TOKEN_NOT_FOUND, $id, $token));
    }

    public static function fromIdAndResetPasswordToken(string $id, string $resetPasswordToken): self
    {
        throw new self(sprintf(self::ID_AND_RESET_PASSWORD_TOKEN_NOT_FOUND, $id, $resetPasswordToken));
    }
}
