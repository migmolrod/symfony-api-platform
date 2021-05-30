<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActivateUserTest extends UserTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testActivateUser(): void
    {
        $rogerId = $this->getRogerId();
        $rogerToken = $this->getRogerToken();
        $payload = [
            'token' => $rogerToken,
        ];

        self::$client->request(
            'PUT',
            sprintf('%s/%s/activate', $this->endpoint, $rogerId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertEquals(true, $responseData['active']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testActivateAlreadyActiveUser(): void
    {
        $brianId = $this->getBrianId();
        $brianToken = $this->getBrianToken();
        $payload = [
            'token' => $brianToken,
        ];

        self::$client->request(
            'PUT',
            sprintf('%s/%s/activate', $this->endpoint, $brianId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$client->getResponse();

        self::assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testActivateNonExistingUser(): void
    {
        $userId = 'non-existing-user-id';
        $userToken = 'non-existing-user-token';
        $payload = [
            'token' => $userToken,
        ];

        self::$client->request(
            'PUT',
            sprintf('%s/%s/activate', $this->endpoint, $userId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$client->getResponse();

        self::assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
