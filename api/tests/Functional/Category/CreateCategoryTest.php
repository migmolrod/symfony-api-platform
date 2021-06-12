<?php

namespace App\Tests\Functional\Category;

use App\Entity\Category;
use App\Exception\Category\CannotCreateCategoryForAnotherGroupException;
use App\Exception\Category\CannotCreateCategoryForAnotherUserException;
use App\Exception\Category\UnsupportedCategoryTypeException;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use function json_encode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateCategoryTest extends CategoryTestBase
{
    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateCategory(): void
    {
        $peterId = $this->getPeterId();
        $payload = [
            'name' => 'new income category',
            'type' => Category::INCOME,
            'owner' => sprintf('/api/v1/users/%s', $peterId),
        ];

        self::$peter->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['name'], $responseData['name']);
        self::assertEquals($payload['type'], $responseData['type']);
        self::assertEquals($payload['owner'], $responseData['owner']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateCategoryForGroup(): void
    {
        $peterId = $this->getPeterId();
        $peterGroupId = $this->getPeterGroupId();
        $payload = [
            'name' => 'new income category',
            'type' => Category::INCOME,
            'owner' => sprintf('/api/v1/users/%s', $peterId),
            'group' => sprintf('/api/v1/groups/%s', $peterGroupId),
        ];

        self::$peter->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['name'], $responseData['name']);
        self::assertEquals($payload['type'], $responseData['type']);
        self::assertEquals($payload['owner'], $responseData['owner']);
        self::assertEquals($payload['group'], $responseData['group']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateCategoryForAnotherUser(): void
    {
        $peterId = $this->getPeterId();
        $payload = [
            'name' => 'new income category',
            'type' => Category::INCOME,
            'owner' => sprintf('/api/v1/users/%s', $peterId),
        ];

        self::$brian->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotCreateCategoryForAnotherUserException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateCategoryForAnotherGroup(): void
    {
        $peterId = $this->getPeterId();
        $brianGroupId = $this->getBrianGroupId();
        $payload = [
            'name' => 'new income category',
            'type' => Category::INCOME,
            'owner' => sprintf('/api/v1/users/%s', $peterId),
            'group' => sprintf('/api/v1/groups/%s', $brianGroupId),
        ];

        self::$peter->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertEquals(CannotCreateCategoryForAnotherGroupException::class, $responseData['class']);
    }

    /**
     * @throws JsonException
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testCreateCategoryWithUnsupportedType(): void
    {
        $peterId = $this->getPeterId();
        $payload = [
            'name' => 'new income category',
            'type' => 'unsupported',
            'owner' => sprintf('/api/v1/users/%s', $peterId),
        ];

        self::$peter->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertEquals(UnsupportedCategoryTypeException::class, $responseData['class']);
    }
}
