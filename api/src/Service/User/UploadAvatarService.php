<?php

namespace App\Service\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatarService
{
    public function __construct()
    {
    }

    public function uploadAvatar(Request $request, User $user): User
    {

    }
}
