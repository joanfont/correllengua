<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Messenger;

use App\Application\Commons\Event\Event;
use App\Application\Commons\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class MessengerEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function dispatch(Event $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
