<?php

namespace App\Tests\Unit\Service\File;

use App\Service\File\FileService;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToDeleteFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function sprintf;

class FileServiceTest extends TestCase
{
    /** @var FilesystemOperator|MockObject */
    private $storage;
    /** @var LoggerInterface|MockObject */
    private $logger;
    /** @var string */
    private string $mediaPath;

    private FileService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->storage = $this->getMockBuilder(FilesystemOperator::class)->disableOriginalConstructor()->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        $this->mediaPath = 'https://storage.test';
        $this->service = new FileService($this->storage, $this->logger, $this->mediaPath);
    }

    /**
     * @throws FilesystemException
     */
    public function testUploadFile(): void
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        $uploadedFile
            ->method('getPathname')
            ->willReturn('/tmp');
        $uploadedFile
            ->method('guessExtension')
            ->willReturn('png');
        $prefix = 'avatar';

        $response = $this->service->uploadFile($uploadedFile, $prefix);

        self::assertIsString($response);
    }

    public function testValidateFile(): void
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();

        $request = new Request([], [], [], [], ['avatar' => $uploadedFile]);

        $response = $this->service->validateFile($request, FileService::AVATAR_INPUT_NAME);

        self::assertInstanceOf(UploadedFile::class, $response);
    }

    public function testValidateInvalidFile(): void
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();

        $request = new Request([], [], [], [], ['wrong' => $uploadedFile]);

        $this->expectException(BadRequestHttpException::class);

        $this->service->validateFile($request, FileService::AVATAR_INPUT_NAME);
    }

    /**
     * @throws FilesystemException
     */
    public function testDeleteFile(): void
    {
        $path = sprintf('%s%s', $this->mediaPath, '/avatar/123.png');

        $this->storage
            ->expects(self::once())
            ->method('delete')
            ->with($path);

        $this->service->deleteFile($path);
    }

    /**
     * @throws FilesystemException
     */
    public function testDeleteNonExistingFile(): void
    {
        $path = sprintf('%s%s', $this->mediaPath, '/avatar/123.png');

        $this->storage
            ->expects(self::once())
            ->method('delete')
            ->with($path)
            ->willThrowException(new UnableToDeleteFile());

        $this->logger
            ->expects(self::once())
            ->method('warning')
            ->with(self::isType('string'));

        $this->service->deleteFile($path);
    }
}
