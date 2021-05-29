<?php

namespace App\Service\File;

use App\Service\Utils\UidGenerator;
use Exception;
use function explode;
use function fopen;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Visibility;
use Psr\Log\LoggerInterface;
use function sprintf;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileService
{
    public const AVATAR_INPUT_NAME = 'avatar';

    private FilesystemOperator $storage;
    private LoggerInterface $logger;
    private string $mediaPath;

    public function __construct(FilesystemOperator $cdnStorage, LoggerInterface $logger, string $mediaPath)
    {
        $this->storage = $cdnStorage;
        $this->logger = $logger;
        $this->mediaPath = $mediaPath;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadFile(UploadedFile $file, string $prefix): string
    {
        $filename = sprintf(
            '%s/%s.%s',
            $prefix,
            UidGenerator::generateUid(),
            $file->guessExtension()
        );

        $this->storage->writeStream(
            $filename,
            fopen($file->getPathname(), 'rb'),
            ['visibility' => Visibility::PUBLIC]
        );

        return $filename;
    }

    /**
     * @throws FilesystemException
     */
    public function deleteFile(?string $path): void
    {
        try {
            if (null !== $path) {
                $this->storage->delete($path);
            }
        } catch (Exception $exception) {
            $this->logger->warning(sprintf('File %s not found in the storage', $path));
        }
    }

    public function validateFile(Request $request, string $inputName): UploadedFile
    {
        if (null === $file = $request->files->get($inputName)) {
            throw new BadRequestHttpException(sprintf('Cannot get file with input name %s', $inputName));
        }

        return $file;
    }
}
