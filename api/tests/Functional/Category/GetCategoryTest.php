<?php

namespace App\Tests\Functional\Category;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetCategoryTest extends CategoryTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetCategory(): void
    {
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterExpenseCategoryId, $responseData['id']);
        self::assertEquals('Peter Expense Category', $responseData['name']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetGroupCategory(): void
    {
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterGroupExpenseCategoryId, $responseData['id']);
        self::assertEquals('Peter Group Expense Category', $responseData['name']);
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherCategory(): void
    {
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherGroupCategory(): void
    {
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
