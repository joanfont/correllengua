<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\Coordinates;
use App\Domain\Model\Entity;
use App\Domain\Model\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Segment extends Entity
{
    /** @var Collection<int, Registration> */
    private readonly Collection $registrations;

    public function __construct(
        private readonly string $id,
        private readonly Itinerary $itinerary,
        private readonly int $position,
        private readonly Coordinates $start,
        private readonly Coordinates $end,
        private readonly int $capacity,
        private readonly Modality $modality,
    ) {
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

    public function capacity(): int
    {
        return $this->capacity;
    }

    public function modality(): Modality
    {
        return $this->modality;
    }

    public function isFull(): bool
    {
        return $this->capacity === $this->registrations->count();
    }

    public function addRegistration(Registration $registration): void
    {
        $this->registrations->add($registration);
    }
}
