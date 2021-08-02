<?php

namespace App\Tests\Functional\Movement;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateMovementTest extends MovementTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateMovement(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterExpenseCategoryId()}",
            'amount' => 333.33,
        ];

        self::$peter->request(
            'PUT',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($payload['category'], $responseData['category']);
        self::assertEquals($payload['amount'], $responseData['amount']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateGroupMovement(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterGroupExpenseCategoryId()}",
            'amount' => 333.33,
        ];

        self::$peter->request(
            'PUT',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($payload['category'], $responseData['category']);
        self::assertEquals($payload['amount'], $responseData['amount']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateMovementForAnotherCategory(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterExpenseCategoryId()}",
            'amount' => 333.33,
        ];

        self::$peter->request(
            'PUT',
            "{$this->endpoint}/{$this->getBrianMovementId()}",
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateMovementForAnotherGroupCategory(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getBrianGroupExpenseCategoryId()}",
            'amount' => 333.33,
        ];

        self::$peter->request(
            'PUT',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateMovementWithInvalidCategory(): void
    {
        $payload = [
            'category' => "/api/v1/categories/category-not-found",
        ];

        self::$peter->request(
            'PUT',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateMovementWithInvalidAmount(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getBrianGroupExpenseCategoryId()}",
            'amount' => 'invalid-amount',
        ];

        self::$peter->request(
            'PUT',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
