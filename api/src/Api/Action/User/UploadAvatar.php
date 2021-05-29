<?php

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\User\UploadAvatarService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatar
{
    private UploadAvatarService $uploadAvatarService;

    public function __construct(UploadAvatarService $uploadAvatarService)
    {
        $this->uploadAvatarService = $uploadAvatarService;
    }

    /**
     * @throws OptimisticLockException
     * @throws FilesystemException
     * @throws ORMException
     */
    public function __invoke(Request $request, User $user): User
    {
        return $this->uploadAvatarService->uploadAvatar($request, $user);
    }
}
