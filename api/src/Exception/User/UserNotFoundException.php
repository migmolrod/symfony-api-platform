<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const EMAIL_NOT_FOUND = 'User with email %s not found';
    private const ID_AND_TOKEN_NOT_FOUND = 'User with id %s and token %s not found or already active';

    public static function fromEmail(string $email): self
    {
        throw new self(\sprintf(self::EMAIL_NOT_FOUND, $email));
    }

    public static function fromIdAndToken(string $id, string $token): self
    {
        throw new self(\sprintf(self::ID_AND_TOKEN_NOT_FOUND, $id, $token));
    }
}
