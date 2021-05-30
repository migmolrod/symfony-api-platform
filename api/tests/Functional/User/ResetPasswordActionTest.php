<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception;
use function json_encode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResetPasswordActionTest extends UserTestBase
{
    /**
     * @throws JsonException
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function testResetPassword(): void
    {
        $peterId = $this->getPeterId();

        $payload = [
            'resetPasswordToken' => '123456',
            'password' => 'new-password',
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/reset-password', $this->endpoint, $peterId),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertEquals($peterId, $responseData['id']);
    }
}
