<?php

namespace App\Tests\Functional\User;

use function sprintf;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

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
