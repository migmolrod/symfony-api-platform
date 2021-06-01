<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use function json_encode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateUserTest extends UserTestBase
{
    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     * @throws JsonException
     */
    public function testUpdateUser(): void
    {
        $payload = ['name' => 'Peter New'];
        $peterId = $this->getPeterId();

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertEquals($payload['name'], $responseData['name']);
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     * @throws JsonException
     */
    public function testUpdateAnotherUser(): void
    {
        $payload = ['name' => 'Peter New'];
        $peterId = $this->getPeterId();

        self::$brian->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testUpdateNonExistingUser(): void
    {
        $payload = ['name' => 'New name'];
        $userId = 'non-existing-user-id';

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $userId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
