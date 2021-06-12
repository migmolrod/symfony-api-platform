<?php

namespace App\Tests\Functional\Group;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupCategoriesTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetUserCategories(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s/categories', $this->endpoint, $peterGroupId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertCount(2, $responseData['hydra:member']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherUserCategories(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s/categories', $this->endpoint, $peterGroupId),
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertCount(0, $responseData['hydra:member']);
    }
}
