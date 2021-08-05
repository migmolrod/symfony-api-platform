<?php

namespace App\Tests\Functional\Movement;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class GetMovementTest extends MovementTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetMovement(): void
    {
        self::$peter->request(
            'GET',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($this->getPeterMovementId(), $responseData['id']);
        self::assertEquals(100, $responseData['amount']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetGroupMovement(): void
    {
        self::$peter->request(
            'GET',
            "{$this->endpoint}/{$this->getPeterGroupMovementId()}",
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($this->getPeterGroupMovementId(), $responseData['id']);
        self::assertEquals(1000, $responseData['amount']);
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherCategory(): void
    {
        self::$brian->request(
            'GET',
            "{$this->endpoint}/{$this->getPeterMovementId()}",
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherGroupCategory(): void
    {
        self::$brian->request(
            'GET',
            "{$this->endpoint}/{$this->getPeterGroupMovementId()}",
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
