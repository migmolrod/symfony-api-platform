<?php

namespace App\Tests\Functional\Movement;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteMovementTest extends MovementTestBase
{
    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteMovement(): void
    {
        self::$peter->request(
            'DELETE',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteGroupMovement(): void
    {
        self::$peter->request(
            'DELETE',
            "{$this->endpoint}/{$this->getPeterGroupMovementId()}",
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteAnotherMovement(): void
    {
        self::$peter->request(
            'DELETE',
            "{$this->endpoint}/{$this->getBrianMovementId()}",
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteAnotherGroupMovement(): void
    {
        self::$peter->request(
            'DELETE',
            "{$this->endpoint}/{$this->getBrianGroupMovementId()}",
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
