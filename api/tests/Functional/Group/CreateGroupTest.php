<?php

namespace App\Tests\Functional\Group;

use App\Exception\Group\CannotCreateGroupForAnotherUserException;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use function json_encode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\Response;

class CreateGroupTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateGroup(): void
    {
        $rogerId = $this->getRogerId();
        $payload = [
            'name' => 'New group',
            'owner' => sprintf('/api/v1/users/%s', $rogerId),
        ];

        self::$roger->request(
            'POST',
            sprintf('%s', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$roger->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['name'], $responseData['name']);
        self::assertEquals($payload['owner'], $responseData['owner']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateGroupForAnotherUser(): void
    {
        $rogerId = $this->getRogerId();
        $payload = [
            'name' => 'New group for another user',
            'owner' => sprintf('/api/v1/users/%s', $rogerId),
        ];

        self::$peter->request(
            'POST',
            sprintf('%s', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotCreateGroupForAnotherUserException::class, $responseData['class']);
    }
}
