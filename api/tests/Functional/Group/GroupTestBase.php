<?php

namespace App\Tests\Functional\Group;

use App\Tests\Functional\TestBase;

class GroupTestBase extends TestBase
{
    protected string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = '/api/v1/groups';
    }
}
