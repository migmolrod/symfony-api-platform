<?php

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\UserRegisterService;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class UserRegister
{
    private UserRegisterService $userRegisterService;

    public function __construct(UserRegisterService $userRegisterService)
    {
        $this->userRegisterService = $userRegisterService;
    }

    /**
     * @throws JsonException
     */
    public function __invoke(Request $request): User
    {
        return $this->userRegisterService->create(
            RequestService::getField($request, 'name'),
            RequestService::getField($request, 'email'),
            RequestService::getField($request, 'password'),
        );
    }
}
