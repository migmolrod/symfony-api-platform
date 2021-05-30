<?php

namespace App\Tests\Functional\User;

use function json_encode;
use JsonException;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginActionTest extends UserTestBase
{
    /**
     * @throws JsonException
     */
    public function testLogin(): void
    {
        $payload = [
            'username' => 'peter@api.com',
            'password' => 'password',
        ];

        self::$peter->request(
            'POST',
            sprintf('%s/login-check', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertInstanceOf(JWTAuthenticationSuccessResponse::class, $response);
    }

    /**
     * @throws JsonException
     */
    public function testLoginWithInvalidCredentials(): void
    {
        $payload = [
            'username' => 'peter@api.com',
            'password' => 'invalid-password',
        ];

        self::$peter->request(
            'POST',
            sprintf('%s/login-check', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertInstanceOf(JWTAuthenticationFailureResponse::class, $response);
    }
}
