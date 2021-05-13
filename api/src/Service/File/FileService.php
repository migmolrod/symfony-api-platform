<?php

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    public function __construct()
    {
    }

    public function uploadFile(UploadedFile $file, string $prefix): string
    {

    }

    public function deleteFile(?string $path): void
    {

    }
}
