<?php

namespace App\Exception\Category;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateCategoryForAnotherGroupException extends AccessDeniedHttpException
{
    private const CATEGORY_FOR_ANOTHER_USER = 'You can\'t create a category for a group you\'re not member of.';

    public function __construct()
    {
        parent::__construct(self::CATEGORY_FOR_ANOTHER_USER);
    }
}
