<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws JsonException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function activate(Request $request, string $id): User
    {
        $token = RequestService::getField($request,'token');
        $user = $this->userRepository->findOneByIdAndTokenOrFail($id, $token);

        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}
