<?php

namespace App\Tests\Functional\Group;

use App\Exception\Group\CannotRemoveAnotherUserIfNotOwnerException;
use App\Exception\Group\CannotRemoveOwnerException;
use App\Exception\Group\UserNotMemberOfGroupException;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class RemoveUserTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testRemoveUser(): void
    {
        $this->addBrianToPeterGroup();
        $brianId = $this->getBrianId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $brianId,
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/remove-user', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('The user has been removed from the group.', $responseData['message']);
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     * @throws JsonException
     */
    private function addBrianToPeterGroup(): void
    {
        $brianId = $this->getBrianId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $brianId,
            'token' => '2345678',
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/accept-request', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );
        self::$peter->getResponse();
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testRemoveSelfUser(): void
    {
        $this->addBrianToPeterGroup();
        $brianId = $this->getBrianId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $brianId,
        ];

        self::$brian->request(
            'PUT',
            sprintf('%s/%s/remove-user', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('The user has been removed from the group.', $responseData['message']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testRemoveNotMemberUser(): void
    {
        $brianId = $this->getBrianId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $brianId,
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/remove-user', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        self::assertEquals(UserNotMemberOfGroupException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testRemoveOwnerUser(): void
    {
        $peterId = $this->getPeterId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $peterId,
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/remove-user', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        self::assertEquals(CannotRemoveOwnerException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testRemoveAnotherUser(): void
    {
        $this->addBrianToPeterGroup();
        $brianId = $this->getBrianId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $brianId,
        ];

        self::$roger->request(
            'PUT',
            sprintf('%s/%s/remove-user', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$roger->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotRemoveAnotherUserIfNotOwnerException::class, $responseData['class']);
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     * @throws JsonException
     */
    public function testRemoveOwner(): void
    {
        $peterId = $this->getPeterId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'userId' => $peterId,
        ];

        self::$roger->request(
            'PUT',
            sprintf('%s/%s/remove-user', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$roger->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        self::assertEquals(CannotRemoveOwnerException::class, $responseData['class']);
    }
}
