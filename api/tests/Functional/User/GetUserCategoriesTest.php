<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserCategoriesTest extends UserTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetUserCategories(): void
    {
        $peterId = $this->getPeterId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s/categories', $this->endpoint, $peterId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertCount(2, $responseData['hydra:member']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherUserCategories(): void
    {
        $peterId = $this->getPeterId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s/categories', $this->endpoint, $peterId),
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertCount(0, $responseData['hydra:member']);
    }
}
