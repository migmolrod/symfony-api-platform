<?php

namespace App\Tests\Functional\Group;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use function json_encode;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class UpdateGroupTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateGroup(): void
    {
        $payload = ['name' => 'New Name'];
        $peterGroupId = $this->getPeterGroupId();

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterGroupId, $responseData['id']);
        self::assertEquals($payload['name'], $responseData['name']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUpdateAnotherGroup(): void
    {
        $payload = ['name' => 'New Name'];
        $peterGroupId = $this->getPeterGroupId();

        self::$brian->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $peterGroupId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
