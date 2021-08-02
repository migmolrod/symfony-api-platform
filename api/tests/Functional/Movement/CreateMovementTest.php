<?php

namespace App\Tests\Functional\Movement;

use App\Exception\Movement\CannotCreateMovementForAnotherGroupException;
use App\Exception\Movement\CannotCreateMovementForAnotherUserException;
use App\Exception\Movement\CannotUseThisCategoryInMovementException;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateMovementTest extends MovementTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateMovement(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getPeterId()}",
            'amount' => 123.45,
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['category'], $responseData['category']);
        self::assertEquals($payload['owner'], $responseData['owner']);
        self::assertEquals($payload['amount'], $responseData['amount']);
        self::assertNull($responseData['group']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateMovementWithAnotherUserCategory(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getBrianExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getPeterId()}",
            'amount' => 123.45,
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotUseThisCategoryInMovementException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateMovementForAnotherUser(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getBrianId()}",
            'amount' => 123.45,
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotCreateMovementForAnotherUserException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateMovementWithInvalidAmount(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getPeterId()}",
            'amount' => 'invalid-amount',
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateGroupMovement(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterGroupExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getPeterId()}",
            'group' => "/api/v1/groups/{$this->getPeterGroupId()}",
            'amount' => 123.45,
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['category'], $responseData['category']);
        self::assertEquals($payload['owner'], $responseData['owner']);
        self::assertEquals($payload['group'], $responseData['group']);
        self::assertEquals($payload['amount'], $responseData['amount']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateGroupMovementWithAnotherUserCategory(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getBrianGroupExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getPeterId()}",
            'group' => "/api/v1/groups/{$this->getPeterGroupId()}",
            'amount' => 123.45,
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotUseThisCategoryInMovementException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateGroupMovementForAnotherGroup(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterGroupExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getPeterId()}",
            'group' => "/api/v1/groups/{$this->getBrianGroupId()}",
            'amount' => 123.45,
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotCreateMovementForAnotherGroupException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateGroupMovementWithUserCategory(): void
    {
        $payload = [
            'category' => "/api/v1/categories/{$this->getPeterExpenseCategoryId()}",
            'owner' => "/api/v1/users/{$this->getPeterId()}",
            'group' => "/api/v1/groups/{$this->getPeterGroupId()}",
            'amount' => 123.45,
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotUseThisCategoryInMovementException::class, $responseData['class']);
    }
}
