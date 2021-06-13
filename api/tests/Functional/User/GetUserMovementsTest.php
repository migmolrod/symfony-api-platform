<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserMovementsTest extends UserTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetUserMovements(): void
    {
        $peterId = $this->getPeterId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s/movements', $this->endpoint, $peterId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertCount(1, $responseData['hydra:member']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherUserMovements(): void
    {
        $peterId = $this->getPeterId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s/movements', $this->endpoint, $peterId),
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertCount(0, $responseData['hydra:member']);
    }
}
