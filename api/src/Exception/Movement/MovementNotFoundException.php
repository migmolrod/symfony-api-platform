<?php

namespace App\Exception\Movement;

use function sprintf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MovementNotFoundException extends NotFoundHttpException
{
    private const ID_NOT_FOUND = 'Movement with id %s not found.';
    private const FILE_PATH_NOT_FOUND = 'Movement with file path %s not found.';

    public static function fromId(string $movementId): self
    {
        throw new self(sprintf(self::ID_NOT_FOUND, $movementId));
    }

    public static function fromFilePath(string $filePath): self
    {
        throw new self(sprintf(self::FILE_PATH_NOT_FOUND, $filePath));
    }
}
