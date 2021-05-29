<?php

namespace App\Service\Utils;

use function sha1;
use function uniqid;

class UidGenerator
{
    public static function generateUid(): string
    {
        return sha1(uniqid('SYM', true));
    }
}
