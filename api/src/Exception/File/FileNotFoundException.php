<?php

namespace App\Exception\File;

use function sprintf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileNotFoundException extends NotFoundHttpException
{
    private const FILE_NOT_FOUND = 'File %s not found.';

    public static function fromPath($path): self
    {
        throw new self(sprintf(self::FILE_NOT_FOUND, $path));
    }
}
