<?php

declare(strict_types=1);

namespace App\Application\Commons\Event;

interface EventBus
{
    public function dispatch(Event $event): void;
}
