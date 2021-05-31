<?php

namespace App\Repository;

use App\Entity\Group;
use App\Exception\Group\GroupNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class GroupRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return Group::class;
    }

    public function findOneByIdOrFail(string $groupId): Group
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $groupId])) {
            throw GroupNotFoundException::fromId($groupId);
        }

        return $user;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Group $group): void
    {
        $this->saveEntity($group);
    }
}
