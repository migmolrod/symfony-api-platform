<?php

namespace App\Api\Listener\PreWriteListener;

use App\Entity\Group;
use App\Entity\User;
use App\Exception\Group\CannotCreateGroupForAnotherUserException;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GroupPreWriteListener implements PreWriteListener
{
    private const GROUP_POST = 'api_groups_post_collection';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelView(ViewEvent $viewEvent): void
    {
        /** @var User|null $user */
        $user = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        $request = $viewEvent->getRequest();
        if (self::GROUP_POST === $request->get('_route')) {
            /** @var Group $group */
            $group = $viewEvent->getControllerResult();

            if (!$group->isOwnedBy($user)) {
                throw new CannotCreateGroupForAnotherUserException();
            }
        }
    }
}
