<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetUserTest extends UserTestBase
{
    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     * @throws JsonException
     */
    public function testGetUser(): void
    {
        $peterId = $this->getPeterId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterId, $responseData['id']);
        self::assertEquals('peter@api.com', $responseData['email']);
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testGetAnotherUser(): void
    {
        $peterId = $this->getPeterId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testGetNonExistingUser(): void
    {
        $userId = 'non-existing-user-id';

        self::$peter->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $userId),
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
