<?php

namespace App\Tests\Functional\Group;

use App\Exception\GroupRequest\GroupRequestNotFoundException;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AcceptRequestTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testAcceptAnAlreadyRequest(): void
    {
        $this->testAcceptRequest();

        $brianId = $this->getBrianId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $brianId,
            'token' => '2345678',
        ];

        self::$client->request(
            'PUT',
            sprintf('%s/%s/accept-request', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertEquals(GroupRequestNotFoundException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testAcceptRequest(): void
    {
        $brianId = $this->getBrianId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $brianId,
            'token' => '2345678',
        ];

        self::$client->request(
            'PUT',
            sprintf('%s/%s/accept-request', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertEquals('The user has been added to the group.', $responseData['message']);
    }
}
