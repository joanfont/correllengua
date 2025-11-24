<?php

namespace App\Application\Commons\Event;

class EventPublisher
{
    /** @var Event[] */
    private array $events = [];

    public function __construct(private readonly EventBus $eventBus) {}

    /**
     * @return Event[]
     */
    public function events(): array
    {
        return $this->events;
    }

    public function publish(Event $event): void
    {
        $this->events[] = $event;
    }

    public function raiseEvents(): void
    {
        foreach ($this->popEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }

    public function reset(): void
    {
        $this->events = [];
    }

    /**
     * @return Event[]
     */
    private function popEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }
}
