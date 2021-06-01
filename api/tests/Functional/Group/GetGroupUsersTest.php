<?php

namespace App\Tests\Functional\Group;

use App\Doctrine\Extension\CurrentUserExtension;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupUsersTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testGetGroupUsers(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s/users', $this->endpoint, $peterGroupId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertCount(1, $responseData['hydra:member']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testGetAnotherGroupUsers(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s/users', $this->endpoint, $peterGroupId),
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CurrentUserExtension::USER_DENIED, $responseData['message']);
    }
}
