<?php

namespace App\Api\Action\Facebook;

use App\Service\Facebook\FacebookService;
use App\Service\Request\RequestService;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Authorization
{
    private FacebookService $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    /**
     * @throws JsonException
     */
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([
            'token' => $this->facebookService->authorize(RequestService::getField($request, 'accessToken')),
        ]);
    }
}
