<?php

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\User\ChangePasswordService;
use Symfony\Component\HttpFoundation\Request;

class ChangePassword
{
    private ChangePasswordService $changePasswordService;

    public function __construct(ChangePasswordService $changePasswordService)
    {
        $this->changePasswordService = $changePasswordService;
    }

    public function __invoke(Request $request, User $user): User
    {
        return $this->changePasswordService->change($request, $user);
    }
}
