<?php

namespace App\Domain\Model;

use App\Application\Commons\Event\Event;

abstract class Entity
{
    protected \DateTimeInterface $createdAt;
    protected \DateTimeInterface $updatedAt;

    /** @var array<int, Event> */
    private array $events = [];

    public function created(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function updated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function events(): array
    {
        return $this->events;
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }

    protected function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }
}
