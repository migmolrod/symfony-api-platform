<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Service\File\FileService;
use App\Service\User\UploadAvatarService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use League\Flysystem\FilesystemException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatarServiceTest extends UserServiceTestBase
{
    /** @var FileService|MockObject  */
    private $fileService;
    private string $mediaPath;

    private UploadAvatarService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->fileService = $this->getMockBuilder(FileService::class)->disableOriginalConstructor()->getMock();
        $this->mediaPath = 'https://storage.test';

        $this->service = new UploadAvatarService($this->userRepository, $this->fileService, $this->mediaPath);
    }

    /**
     * @throws OptimisticLockException
     * @throws FilesystemException
     * @throws ORMException
     */
    public function testUploadAvatar(): void
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $file = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        $user = new User('username', 'username@api.com');
        $user->setAvatar('old-image.png');

        $this->fileService
            ->expects(self::once())
            ->method('validateFile')
            ->with($request, FileService::AVATAR_INPUT_NAME)
            ->willReturn($file);

        $this->fileService
            ->expects(self::once())
            ->method('deleteFile')
            ->with($user->getAvatar());

        $this->fileService
            ->expects(self::once())
            ->method('uploadFile')
            ->with($file, FileService::AVATAR_INPUT_NAME)
            ->willReturn('new-image.png');

        $response = $this->service->uploadAvatar($request, $user);

        self::assertEquals('new-image.png', $user->getAvatar());
    }
}
