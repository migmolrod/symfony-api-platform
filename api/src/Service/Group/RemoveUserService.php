<?php

namespace App\Service\Group;

use App\Exception\Group\CannotRemoveAnotherUserIfNotOwnerException;
use App\Exception\Group\CannotRemoveOwnerException;
use App\Exception\Group\NotOwnerOfGroupException;
use App\Exception\Group\UserNotMemberOfGroupException;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Throwable;

class RemoveUserService
{
    private UserRepository $userRepository;
    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Throwable
     */
    public function remove(string $groupId, string $userId, string $requesterId): void
    {
        $group = $this->groupRepository->findOneByIdOrFail($groupId);
        $user = $this->userRepository->findOneByIdOrFail($userId);
        $requester = $this->userRepository->findOneByIdOrFail($requesterId);

        if (!$requester->equals($user) && !$group->isOwnedBy($requester)) {
            throw new CannotRemoveAnotherUserIfNotOwnerException();
        }

        if (!$user->isMemberOfGroup($group)) {
            throw new UserNotMemberOfGroupException();
        }

        if ($group->isOwnedBy($user)) {
            throw new CannotRemoveOwnerException();
        }

        $group->removeUser($user);
        $user->removeGroup($group);

        $this->groupRepository->getEntityManager()->transactional(
            function(EntityManagerInterface $em) use ($group) {
                $em->persist($group);
            }
        );
    }
}
