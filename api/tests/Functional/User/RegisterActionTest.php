<?php

namespace App\Tests\Functional\User;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterActionTest extends UserTestBase
{
    /**
     * @throws JsonException
     */
    public function testRegister(): void
    {
        $payload = [
            'name' => 'user1',
            'email' => 'user1@api.com',
            'password' => '123456',
        ];

        self::$client->request(
            'POST',
            sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['email'], $responseData['email']);
    }

    /**
     * @throws JsonException
     */
    public function testRegisterWithMissingParameters(): void
    {
        $payload = [
            'name' => 'user1',
            'password' => '123456',
        ];

        self::$client->request(
            'POST',
            sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testRegisterWithInvalidPassword(): void
    {
        $payload = [
            'name' => 'user1',
            'email' => 'user1@api.com',
            'password' => '1',
        ];

        self::$client->request(
            'POST',
            sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
