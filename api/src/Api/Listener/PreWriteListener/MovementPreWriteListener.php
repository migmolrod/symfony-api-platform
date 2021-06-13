<?php

namespace App\Api\Listener\PreWriteListener;

use App\Entity\Movement;
use App\Entity\User;
use App\Exception\Movement\CannotCreateMovementForAnotherGroupException;
use App\Exception\Movement\CannotCreateMovementForAnotherUserException;
use App\Exception\Movement\CannotUseThisCategoryInMovementException;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function in_array;

class MovementPreWriteListener implements PreWriteListener
{
    private const MOVEMENT_POST = 'api_movements_post_collection';
    private const MOVEMENT_PUT = 'api_movements_put_item';

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

        if (in_array($request->get('_route'), $this->movementRoutes(), true)) {
            /** @var Movement $movement */
            $movement = $viewEvent->getControllerResult();

            if (null !== $group = $movement->getGroup()) {
                if (!$user->isMemberOfGroup($group)) {
                    throw new CannotCreateMovementForAnotherGroupException();
                }

                if (!$movement->getCategory()->belongsToGroup($group)) {
                    throw CannotUseThisCategoryInMovementException::fromInvalidCategory($movement->getCategory()->getName());
                }
            }

            if (!$movement->isOwnedBy($user)) {
                throw new CannotCreateMovementForAnotherUserException();
            }

            if (!$movement->getCategory()->isOwnedBy($user)) {
                throw CannotUseThisCategoryInMovementException::fromInvalidCategory($movement->getCategory()->getName());
            }
        }
    }

    private function movementRoutes(): array
    {
        return [self::MOVEMENT_POST, self::MOVEMENT_PUT];
    }
}
