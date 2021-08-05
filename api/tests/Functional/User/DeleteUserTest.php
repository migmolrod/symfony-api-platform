<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use function sprintf;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserTest extends UserTestBase
{
    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testDeleteUser(): void
    {
        $peterId = $this->getPeterId();

        self::$peter->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterId),
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testDeleteAnotherUser(): void
    {
        $peterId = $this->getPeterId();

        self::$brian->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $peterId),
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testDeleteNonExistingUser(): void
    {
        $userId = 'non-existing-user-id';

        self::$peter->request(
            'DELETE',
            sprintf('%s/%s', $this->endpoint, $userId),
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
