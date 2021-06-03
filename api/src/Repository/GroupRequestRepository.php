<?php

namespace App\Repository;

use App\Entity\GroupRequest;
use App\Exception\GroupRequest\GroupRequestNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class GroupRequestRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return GroupRequest::class;
    }

    public function findOneByGroupAndUserAndTokenOrFail(string $groupId, string $userId, string $token): GroupRequest
    {
        $groupRequest = $this->objectRepository->findOneBy([
            'group' => $groupId,
            'user' => $userId,
            'token' => $token,
            'status' => GroupRequest::PENDING,
        ]);
        if (null === $groupRequest) {
            throw GroupRequestNotFoundException::fromGroupAndUserAndToken($groupId, $userId, $token);
        }

        return $groupRequest;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(GroupRequest $groupRequest): void
    {
        $this->saveEntity($groupRequest);
    }
}
