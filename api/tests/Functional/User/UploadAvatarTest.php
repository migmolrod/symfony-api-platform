<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Driver\Exception;
use function sprintf;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadAvatarTest extends UserTestBase
{
    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function testUploadAvatar(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/avatar.png',
            'avatar.png'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/avatar', $this->endpoint, $this->getPeterId()),
            [],
            ['avatar' => $avatar]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUploadWithInvalidInputName(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/avatar.png',
            'avatar.png'
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/avatar', $this->endpoint, $this->getPeterId()),
            [],
            ['wrong' => $avatar]
        );

        $response = self::$peter->getResponse();

        self::assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
