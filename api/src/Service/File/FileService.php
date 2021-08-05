<?php

namespace App\Service\File;

use App\Exception\File\FileNotFoundException;
use App\Service\Utils\UidGenerator;
use Exception;
use function fopen;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use function sprintf;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileService
{
    public const AVATAR_INPUT_NAME = 'avatar';
    public const MOVEMENT_INPUT_NAME = 'file';

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
    public function uploadFile(UploadedFile $file, string $prefix, string $visibility): string
    {
        $filename = sprintf(
            '%s/%s.%s',
            $prefix,
            UidGenerator::generateToken(),
            $file->guessExtension()
        );

        $this->storage->writeStream(
            $filename,
            fopen($file->getPathname(), 'rb'),
            ['visibility' => $visibility]
        );

        return $filename;
    }

    public function downloadFile(string $path): ?string
    {
        try {
            return $this->storage->read($path);
        } catch (FilesystemException $exception) {
            throw FileNotFoundException::fromPath($path);
        }
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
