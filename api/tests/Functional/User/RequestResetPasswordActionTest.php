<?php

namespace App\Tests\Functional\User;

use function json_encode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class RequestResetPasswordActionTest extends UserTestBase
{
    /**
     * @throws JsonException
     */
    public function testRequestResetPassword(): void
    {
        $payload = [
            'email' => 'peter@api.com',
        ];

        self::$peter->request(
            'POST',
            sprintf('%s/request-reset-password', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testRequestResetPasswordForNonExistingEmail(): void
    {
        $payload = [
            'email' => 'non-existing@api.com',
        ];

        self::$peter->request(
            'POST',
            sprintf('%s/request-reset-password', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
