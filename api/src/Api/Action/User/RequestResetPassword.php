<?php

namespace App\Api\Action\User;

use App\Service\User\RequestResetPasswordService;
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

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(['message' => 'Request reset password email sent']);
    }
}
