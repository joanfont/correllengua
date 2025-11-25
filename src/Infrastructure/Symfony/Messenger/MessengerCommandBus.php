<?php

namespace App\Infrastructure\Symfony\Messenger;

use App\Application\Commons\Command\Command;
use App\Application\Commons\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
