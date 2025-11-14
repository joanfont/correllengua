<?php

namespace App\Infrastructure\Symfony\Messenger\Middleware;


use App\Application\Commons\Event\EventPublisher;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class RaiseEntityEventsMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly EventPublisher $eventPublisher)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->eventPublisher->reset();
        $envelope = $stack->next()->handle($envelope, $stack);
        $this->eventPublisher->raiseEvents();

        return $envelope;
    }
}