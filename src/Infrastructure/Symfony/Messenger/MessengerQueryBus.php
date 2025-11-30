<?php

namespace App\Infrastructure\Symfony\Messenger;

namespace App\Infrastructure\Symfony\Messenger;

use App\Application\Commons\Query\Query;
use App\Application\Commons\Query\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function query(Query $query): mixed
    {
        return $this->handle($query);
    }
}
