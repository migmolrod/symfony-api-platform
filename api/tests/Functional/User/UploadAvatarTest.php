<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use function sprintf;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadAvatarTest extends UserTestBase
{
    public function testUploadAvatar(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/avatar.png',
            'avatar.png'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/avatar', $this->endpoint),
            [],
            ['avatar' => $avatar]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUploadWithInvalidInputName(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/avatar.png',
            'avatar.png'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/avatar', $this->endpoint),
            [],
            ['wrong' => $avatar]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
