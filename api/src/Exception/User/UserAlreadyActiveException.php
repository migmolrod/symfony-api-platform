<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserAlreadyActiveException extends ConflictHttpException
{
    private const EMAIL_ALREADY_ACTIVE = 'User with email %s is already active';
    private const ID_AND_TOKEN_ALREADY_ACTIVE = 'User with id %s and token %s is already active';

    public static function fromEmail(string $email): self
    {
        throw new self(\sprintf(self::EMAIL_ALREADY_ACTIVE, $email));
    }

    public static function fromIdAndToken(string $id, string $token): self
    {
        throw new self(\sprintf(self::ID_AND_TOKEN_ALREADY_ACTIVE, $id, $token));
    }
}
