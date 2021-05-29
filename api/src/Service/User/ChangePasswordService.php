<?php

namespace App\Service\User;

use App\Entity\User;
use App\Exception\Password\PasswordException;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ChangePasswordService
{
    private UserRepository $userRepository;
    private EncoderService $encoderService;

    public function __construct(UserRepository $userRepository, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function change(string $userId, string $oldPassword, string $newPassword): User
    {
        $user = $this->userRepository->findOneByIdOrFail($userId);
        if (!$this->encoderService->isValidPassword($user, $oldPassword)) {
            throw PasswordException::oldPasswordDoesNotMatch();
        }

        $user->setPassword($this->encoderService->generateEncodedPassword($user, $newPassword));
        $user->setResetPasswordToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}
