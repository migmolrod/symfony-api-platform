<?php

namespace App\Api\Action\User;

use App\Service\Request\RequestService;
use App\Service\User\ResendActivationEmailService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResendActivationEmail
{
    private ResendActivationEmailService $resendActivationEmailService;

    public function __construct(ResendActivationEmailService $resendActivationEmailService)
    {
        $this->resendActivationEmailService = $resendActivationEmailService;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws JsonException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->resendActivationEmailService->resend(
            RequestService::getField($request, 'email'),
        );

        return new JsonResponse(['message' => 'Activation email sent.']);
    }
}
