<?php

namespace App\Application\Commons\Event;

interface EventBus
{
    public function dispatch(Event $event): void;
}