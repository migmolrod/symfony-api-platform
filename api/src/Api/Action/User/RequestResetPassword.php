<?php

namespace App\Api\Action\User;

use App\Service\User\RequestResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RequestResetPassword
{
    private RequestResetPasswordService $requestResetPasswordService;

    /**
     * RequestResetPassword constructor.
     */
    public function __construct(RequestResetPasswordService $requestResetPasswordService)
    {
        $this->requestResetPasswordService = $requestResetPasswordService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws JsonException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->requestResetPasswordService->send($request);

        return new JsonResponse(['message' => 'Request reset password email sent']);
    }
}
