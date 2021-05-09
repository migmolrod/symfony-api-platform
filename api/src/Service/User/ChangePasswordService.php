<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordService
{

    public function __construct(UserRepository $userRepository, EncoderService $encoderService)
    {
    }

    public function change(Request $request, User $user): User
    {
        return $user;
    }
}
