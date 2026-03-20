<?php

declare(strict_types=1);

namespace App\Domain\Model\Route;

use App\Domain\Model\Coordinates;
use App\Domain\Model\Entity;
use App\Domain\Model\Registration\Registration;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Segment extends Entity
{
    private string $id;

    /** @var Collection<int, Registration> */
    private Collection $registrations;

    public function __construct(
        SegmentId $id,
        private Itinerary $itinerary,
        private int $position,
        private Coordinates $start,
        private Coordinates $end,
        private ?int $capacity,
        private Modality $modality,
        private DateTimeInterface $startTime,
    ) {
        $this->id = (string) $id;
        $this->registrations = new ArrayCollection();
    }

    public function id(): SegmentId
    {
        return SegmentId::from($this->id);
    }

    public function itinerary(): Itinerary
    {
        return $this->itinerary;
    }

    public function position(): int
    {
        return $this->position;
    }

    public function start(): Coordinates
    {
        return $this->start;
    }

    public function end(): Coordinates
    {
        return $this->end;
    }

    public function capacity(): ?int
    {
        return $this->capacity;
    }

    public function freeSlots(): ?int
    {
        if (null === $this->capacity) {
            return null;
        }

        return $this->capacity - $this->registrations->count();
    }

    public function modality(): Modality
    {
        return $this->modality;
    }

    public function startTime(): DateTimeInterface
    {
        return $this->startTime;
    }

    public function isFull(): bool
    {
        return null !== $this->capacity && $this->capacity === $this->registrations->count();
    }

    public function registrationsCount(): int
    {
        return $this->registrations->count();
    }

    public function remainingCapacity(): ?int
    {
        return null === $this->capacity ? null : $this->capacity - $this->registrations->count();
    }

    public function addRegistration(Registration $registration): void
    {
        $this->registrations->add($registration);
    }
}
