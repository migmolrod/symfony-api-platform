<?php

namespace App\Tests\Functional\Group;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class GetGroupTest extends GroupTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetGroup(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$peter->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterGroupId),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterGroupId, $responseData['id']);
        self::assertEquals('Peter Group', $responseData['name']);
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testGetAnotherGroup(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$brian->request(
            'GET',
            sprintf('%s/%s', $this->endpoint, $peterGroupId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
