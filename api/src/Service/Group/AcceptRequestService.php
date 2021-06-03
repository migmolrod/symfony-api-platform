<?php

namespace App\Service\Group;

use App\Repository\GroupRepository;
use App\Repository\GroupRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class AcceptRequestService
{
    private GroupRequestRepository $groupRequestRepository;
    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(GroupRequestRepository $groupRequestRepository, GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRequestRepository = $groupRequestRepository;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Throwable
     */
    public function accept(string $groupId, string $userId, string $token): void
    {
        $this->groupRequestRepository->getEntityManager()->transactional(
            function (EntityManagerInterface $em) use ($groupId, $userId, $token) {
                $groupRequest = $this->groupRequestRepository->findOneByGroupAndUserAndTokenOrFail($groupId, $userId, $token);
                $groupRequest->markAsAccepted();

                $em->persist($groupRequest);

                $group = $this->groupRepository->findOneByIdOrFail($groupId);
                $user = $this->userRepository->findOneByIdOrFail($userId);

                $group->addUser($user);
                $user->addGroup($group);

                $em->persist($group);
            }
        );
    }
}
