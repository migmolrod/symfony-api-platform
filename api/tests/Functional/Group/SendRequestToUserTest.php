<?php

namespace App\Tests\Functional\Group;

use App\Exception\Group\CannotCreateGroupForAnotherUserException;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class SendRequestToUserTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testSendRequestToUser(): void
    {
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'email' => 'roger@api.com',
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/send-request', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertEquals('The request has been sent.', $responseData['message']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testSendRequestToUserAsNotOwner(): void
    {
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'email' => 'roger@api.com',
        ];

        self::$brian->request(
            'PUT',
            sprintf('%s/%s/send-request', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals('You are not the owner of this group.', $responseData['message']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testSendRequestToAlreadyMemberUser(): void
    {
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'email' => 'peter@api.com',
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/send-request', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
        self::assertEquals('The user is already member of the group.', $responseData['message']);
    }
}
