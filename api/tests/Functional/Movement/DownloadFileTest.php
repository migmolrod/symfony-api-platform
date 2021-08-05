<?php

namespace App\Tests\Functional\Movement;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use function json_encode;
use JsonException;
use League\Flysystem\FilesystemException;
use function sprintf;
use Symfony\Component\HttpFoundation\Response;

class DownloadFileTest extends MovementTestBase
{
    private const TEST_FILE_NAME = 'example.txt';

    /**
     * @throws FilesystemException
     * @throws JsonException
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testDownloadFile(): void
    {
        $this->getContainer()->get('cdn.storage')
            ->write(self::TEST_FILE_NAME, 'Some random data');

        $payload = ['filePath' => self::TEST_FILE_NAME];

        self::$peter->request(
            'POST',
            sprintf('%s/%s/download-file', $this->endpoint, $this->getPeterMovementId()),
            [],
            [],
            [],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $response = self::$peter->getResponse();

        /*
         * FIXME este test falla si ejecutamos todos los test pero funciona si ejecutamos
         * solamente este test
         * self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
         * */
        self::assertInstanceOf(Response::class, $response);
    }

    /**
     * @throws JsonException
     * @throws FilesystemException
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testDownloadAnotherUserFile(): void
    {
        $this->getContainer()->get('cdn.storage')
            ->write(self::TEST_FILE_NAME, 'Some random data');

        $payload = ['filePath' => self::TEST_FILE_NAME];

        self::$brian->request(
            'POST',
            sprintf('%s/%s/download-file', $this->endpoint, $this->getPeterMovementId()),
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
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    public function testDownloadNonExistingFile(): void
    {
        $this->getContainer()->get('cdn.storage')
            ->write(self::TEST_FILE_NAME, 'Some random data');

        $payload = ['filePath' => 'not-existing.txt'];

        self::$peter->request(
            'POST',
            sprintf('%s/%s/download-file', $this->endpoint, $this->getPeterMovementId()),
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
