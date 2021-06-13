<?php

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use function sprintf;

class CannotUseThisCategoryInMovementException extends AccessDeniedHttpException
{
    private const INVALID_CATEGORY_IN_MOVEMENT = 'You can\'t use the category %s in this movement.';

    public static function fromInvalidCategory(string $category): self
    {
        return new self(sprintf(self::INVALID_CATEGORY_IN_MOVEMENT, $category));
    }
}
