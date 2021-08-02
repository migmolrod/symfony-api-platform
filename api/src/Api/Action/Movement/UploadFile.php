<?php

namespace App\Api\Action\Movement;

use App\Entity\Movement;
use App\Entity\User;
use App\Service\Movement\UploadFileService;
use Symfony\Component\HttpFoundation\Request;

class UploadFile
{
    private UploadFileService $uploadFileService;

    public function __construct(UploadFileService $uploadFileService)
    {
        $this->uploadFileService = $uploadFileService;
    }

    public function __invoke(Request $request, User $user, string $id): Movement
    {
        return $this->uploadFileService->uploadFile($request, $user, $id);
    }
}
