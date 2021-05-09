<?php

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\User\ResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class ResetPassword
{
    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {

        $this->resetPasswordService = $resetPasswordService;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws JsonException
     */
    public function __invoke(Request $request): User
    {
        return $this->resetPasswordService->reset($request);
    }
}
