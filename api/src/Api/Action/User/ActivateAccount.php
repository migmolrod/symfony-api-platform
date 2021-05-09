<?php

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\ActivateAccountService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccount
{
    private ActivateAccountService $activateAccountService;

    public function __construct(ActivateAccountService $activateAccountService)
    {
        $this->activateAccountService = $activateAccountService;
    }

    /**
     * @throws OptimisticLockException
     * @throws JsonException
     * @throws ORMException
     */
    public function __invoke(Request $request, string $id): User
    {
        return $this->activateAccountService->activate($id, RequestService::getField($request, 'token'));
    }
}
