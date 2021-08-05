<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class GetUserGroupsTest extends UserTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testGetUserGroups(): void
    {
        $peterId = $this->getPeterId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s/groups', $this->endpoint, $peterId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertCount(1, $responseData['hydra:member']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testGetAnotherUserGroups(): void
    {
        $peterId = $this->getPeterId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s/groups', $this->endpoint, $peterId),
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
