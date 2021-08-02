<?php

namespace App\Tests\Functional\Category;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateCategoryTest extends CategoryTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateCategory(): void
    {
        $payload = ['name' => 'New Name'];
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterExpenseCategoryId, $responseData['id']);
        self::assertEquals($payload['name'], $responseData['name']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateGroupCategory(): void
    {
        $payload = ['name' => 'New Name'];
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterGroupExpenseCategoryId, $responseData['id']);
        self::assertEquals($payload['name'], $responseData['name']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateAnotherCategory(): void
    {
        $payload = ['name' => 'New Name'];
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$brian->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateAnotherGroupCategory(): void
    {
        $payload = ['name' => 'New Name'];
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$brian->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
