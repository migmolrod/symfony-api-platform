<?php

namespace App\Tests\Functional\Category;

use App\Tests\Functional\TestBase;

class CategoryTestBase extends TestBase
{
    protected string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = '/api/v1/categories';
    }
}
