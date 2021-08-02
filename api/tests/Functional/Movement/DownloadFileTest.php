<?php

namespace App\Tests\Functional\Movement;

use JsonException;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;

class DownloadFileTest extends MovementTestBase
{
    private const TEST_FILE_NAME = 'example.txt';

    /**
     * @throws JsonException
     * @throws FilesystemException
     */
    public function testDownloadFile(): void
    {
        $this->getContainer()->get('cdn.storage')
            ->write(self::TEST_FILE_NAME, 'Some random data');

        $payload = ['filePath' => self::TEST_FILE_NAME];

        self::$peter->request(
            'POST',
            \sprintf('%s/file', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertInstanceOf(Response::class, $response);
    }

    /**
     * @throws JsonException
     * @throws FilesystemException
     */
    public function testDownloadAnotherUserFile(): void
    {
        $this->getContainer()->get('cdn.storage')
            ->write(self::TEST_FILE_NAME, 'Some random data');

        $payload = ['filePath' => self::TEST_FILE_NAME];

        self::$brian->request(
            'POST',
            \sprintf('%s/file', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$brian->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        self::assertInstanceOf(Response::class, $response);
    }

    /**
     * @throws JsonException
     * @throws FilesystemException
     */
    public function testDownloadNonExistingFile(): void
    {
        $this->getContainer()->get('cdn.storage')
            ->write(self::TEST_FILE_NAME, 'Some random data');

        $payload = ['filePath' => 'not-existing.txt'];

        self::$peter->request(
            'POST',
            \sprintf('%s/file', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertInstanceOf(Response::class, $response);
    }
}
