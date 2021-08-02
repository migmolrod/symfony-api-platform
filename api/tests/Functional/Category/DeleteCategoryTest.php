<?php

namespace App\Tests\Functional\Category;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use Symfony\Component\HttpFoundation\Response;

class DeleteCategoryTest extends CategoryTestBase
{
    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteCategory(): void
    {
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$peter->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId),
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteGroupCategory(): void
    {
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$peter->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId),
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteAnotherCategory(): void
    {
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$brian->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteAnotherGroupCategory(): void
    {
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$brian->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
