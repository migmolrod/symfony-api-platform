<?php

namespace App\Exception\Category;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateCategoryForAnotherUserException extends AccessDeniedHttpException
{
    private const CATEGORY_FOR_ANOTHER_USER = 'You can\'t create a category for another user.';

    public function __construct()
    {
        parent::__construct(self::CATEGORY_FOR_ANOTHER_USER);
    }
}
