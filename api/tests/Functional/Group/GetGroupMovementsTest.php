<?php

namespace App\Tests\Functional\Group;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupMovementsTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetGroupMovements(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s/movements', $this->endpoint, $peterGroupId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertCount(1, $responseData['hydra:member']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherGroupMovements(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s/movements', $this->endpoint, $peterGroupId),
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertCount(0, $responseData['hydra:member']);
    }
}
