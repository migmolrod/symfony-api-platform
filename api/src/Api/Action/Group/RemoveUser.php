<?php

namespace App\Api\Action\Group;

use App\Entity\User;
use App\Service\Group\RemoveUserService;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class RemoveUser
{
    private RemoveUserService $removeUserService;

    public function __construct(RemoveUserService $removeUserService)
    {
        $this->removeUserService = $removeUserService;
    }

    /**
     * @throws JsonException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Throwable
     */
    public function __invoke(Request $request, User $user, string $id): JsonResponse
    {
        $this->removeUserService->remove(
            $id,
            RequestService::getField($request, 'userId'),
            $user->getId(),
        );

        return new JsonResponse(['message' => 'The user has been removed from the group.']);
    }
}
