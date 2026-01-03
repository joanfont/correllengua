<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Application\Commons\Event\Event;
use DateTime;
use DateTimeInterface;

abstract class Entity
{
    protected DateTimeInterface $createdAt;

    protected DateTimeInterface $updatedAt;

    /** @var array<Event> */
    private array $events = [];

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function created(): void
    {
        $this->createdAt = new DateTime();
    }

    public function updated(): void
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * @return array<Event>
     */
    public function events(): array
    {
        return $this->events;
    }

    protected function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }
}
