<?php

namespace App\Messenger;

abstract class RoutingKey
{
    public const USER_QUEUE = 'user_queue';
    public const GROUP_QUEUE = 'group_queue';
}
