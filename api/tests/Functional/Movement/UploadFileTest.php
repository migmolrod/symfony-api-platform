<?php

namespace App\Tests\Functional\Movement;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use function sprintf;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UploadFileTest extends MovementTestBase
{
    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUploadFileForUser(): void
    {
        $file = new UploadedFile(
            __DIR__.'/../../../fixtures/ticket-metro-madrid.jpeg',
            'ticket.jpeg'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/upload-file', $this->endpoint, $this->getPeterMovementId()),
            [],
            ['file' => $file]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUploadFileForGroup(): void
    {
        $file = new UploadedFile(
            __DIR__.'/../../../fixtures/ticket-metro-madrid.jpeg',
            'ticket.jpeg'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/upload-file', $this->endpoint, $this->getPeterGroupMovementId()),
            [],
            ['file' => $file]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUploadFileForAnotherUser(): void
    {
        $file = new UploadedFile(
            __DIR__.'/../../../fixtures/ticket-metro-madrid.jpeg',
            'ticket.jpeg'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/upload-file', $this->endpoint, $this->getBrianMovementId()),
            [],
            ['file' => $file]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUploadFileForAnotherGroup(): void
    {
        $file = new UploadedFile(
            __DIR__.'/../../../fixtures/ticket-metro-madrid.jpeg',
            'ticket.jpeg'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/upload-file', $this->endpoint, $this->getBrianGroupMovementId()),
            [],
            ['file' => $file]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @throws DoctrineDbalException
     * @throws DoctrineDbalDriverException
     */
    public function testUploadFileWithInvalidInputName(): void
    {
        $file = new UploadedFile(
            __DIR__.'/../../../fixtures/ticket-metro-madrid.jpeg',
            'ticket.jpeg'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/upload-file', $this->endpoint, $this->getPeterMovementId()),
            [],
            ['wrong' => $file]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
