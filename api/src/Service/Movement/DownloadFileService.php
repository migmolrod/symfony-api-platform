<?php

namespace App\Service\Movement;

use App\Entity\User;
use App\Exception\Movement\MovemenDoesNotBelongException;
use App\Repository\MovementRepository;
use App\Service\File\FileService;

class DownloadFileService
{
    private MovementRepository $movementRepository;
    private FileService $fileService;

    public function __construct(MovementRepository $movementRepository, FileService $fileService)
    {
        $this->movementRepository = $movementRepository;
        $this->fileService = $fileService;
    }

    public function downloadFile(User $user, string $filePath): ?string
    {
        $movement = $this->movementRepository->findOneByFilePathOrFail($filePath);

        if ((null !== $group = $movement->getGroup()) && !$user->isMemberOfGroup($group)) {
            throw MovemenDoesNotBelongException::doesNotBelongToGroup($movement->getId(), $group->getId());
        }
        if (!$movement->isOwnedBy($user)) {
            throw MovemenDoesNotBelongException::doesNotBelongToUser($movement->getId(), $user->getId());
        }

        return $this->fileService->downloadFile($filePath);
    }
}
