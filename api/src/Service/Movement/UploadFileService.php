<?php

namespace App\Service\Movement;

use App\Entity\Movement;
use App\Entity\User;
use App\Exception\Movement\MovemenDoesNotBelongException;
use App\Repository\MovementRepository;
use App\Service\File\FileService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use League\Flysystem\FilesystemException;
use League\Flysystem\Visibility;
use Symfony\Component\HttpFoundation\Request;

class UploadFileService
{
    private FileService $fileService;
    private MovementRepository $movementRepository;

    public function __construct(FileService $fileService, MovementRepository $movementRepository)
    {
        $this->fileService = $fileService;
        $this->movementRepository = $movementRepository;
    }

    /**
     * @throws FilesystemException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function uploadFile(Request $request, User $user, string $id): Movement
    {
        $movement = $this->movementRepository->findOneByIdOrFail($id);

        if ((null !== $group = $movement->getGroup()) && !$user->isMemberOfGroup($group)) {
            throw MovemenDoesNotBelongException::doesNotBelongToGroup($movement->getId(), $group->getId());
        }

        if (!$movement->isOwnedBy($user)) {
            throw MovemenDoesNotBelongException::doesNotBelongToUser($movement->getId(), $user->getId());
        }

        $file = $this->fileService->validateFile($request, FileService::MOVEMENT_INPUT_NAME);
        $this->fileService->deleteFile($movement->getFilePath());
        $filename = $this->fileService->uploadFile(
            $file,
            FileService::MOVEMENT_INPUT_NAME,
            Visibility::PRIVATE
        );

        $movement->setFilePath($filename);
        $this->movementRepository->save($movement);

        return $movement;
    }
}
