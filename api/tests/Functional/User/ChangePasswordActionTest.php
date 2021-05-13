<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Exception;
use function json_encode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChangePasswordActionTest extends UserTestBase
{
    /**
     * @throws JsonException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    public function testChangePassword(): void
    {
        $payload = [
            'oldPassword' => 'password',
            'newPassword' => 'new-password',
        ];

        self::$brian->request(
            'PUT',
            sprintf('%s/%s/change-password', $this->endpoint, $this->getBrianId()),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$brian->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    public function testChangePasswordWithInvalidOldPassword(): void
    {
        $payload = [
            'oldPassword' => 'invalid-password',
            'newPassword' => 'new-password',
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/change-password', $this->endpoint, $this->getPeterId()),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
