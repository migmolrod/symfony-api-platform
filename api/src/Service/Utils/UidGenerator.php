<?php

namespace App\Service\Utils;

use function sha1;
use Symfony\Component\Uid\Uuid;
use function uniqid;

class UidGenerator
{
    public static function generateToken(): string
    {
        return sha1(uniqid('SYM', true));
    }

    public static function generateId(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
