<?php

namespace App\Tests\Functional\User;

use function json_encode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResendActivationEmailActionTest extends UserTestBase
{
    /**
     * @throws JsonException
     */
    public function testResendActivationEmail(): void
    {
        $payload = [
            'email' => 'roger@api.com',
        ];

        self::$roger->request(
            'POST',
            sprintf('%s/resend-activation-email', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$roger->getResponse();

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testResendActivationEmailToActiveUser(): void
    {
        $payload = [
            'email' => 'peter@api.com',
        ];

        self::$peter->request(
            'POST',
            sprintf('%s/resend-activation-email', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testResendActivationEmailToNonExistingUser(): void
    {
        $payload = [
            'email' => 'non-existing@api.com',
        ];

        self::$client->request(
            'POST',
            sprintf('%s/resend-activation-email', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$client->getResponse();

        self::assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
