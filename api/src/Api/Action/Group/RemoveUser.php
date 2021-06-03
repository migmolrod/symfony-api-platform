<?php

namespace App\Api\Action\Group;

use Symfony\Component\HttpFoundation\JsonResponse;

class RemoveUser
{
    public function __construct()
    {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['message' => '']);
    }
}
