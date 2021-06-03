<?php

namespace App\Exception\User;

use function sprintf;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserAlreadyActiveException extends ConflictHttpException
{
    private const MESSAGE = 'User with email %s is already active.';

    public static function fromEmail(string $email): self
    {
        throw new self(sprintf(self::MESSAGE, $email));
    }
}
