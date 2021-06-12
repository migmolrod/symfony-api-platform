<?php

namespace App\Exception\Category;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function sprintf;

class UnsupportedCategoryTypeException extends BadRequestHttpException
{
    private const UNSUPPORTED_CATEGORY_TYPE = 'The category type \'%s\' is not supported.';

    public static function fromType(string $type): self
    {
        throw new self(sprintf(self::UNSUPPORTED_CATEGORY_TYPE, $type));
    }
}
