<?php

namespace App\Tests\Functional\Group;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteGroupTest extends GroupTestBase
{
    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteGroup(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$peter->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterGroupId),
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testDeleteAnotherGroup(): void
    {
        $peterGroupId = $this->getPeterGroupId();

        self::$brian->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterGroupId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
