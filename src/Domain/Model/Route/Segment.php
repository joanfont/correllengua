<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\Coordinates;
use App\Domain\Model\Entity;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Segment extends Entity
{
    private string $id;

    /** @var Collection<Registration> */
    private Collection $registrations;

    public function __construct(
        SegmentId $id,
        private Route $route,
        private int $position,
        private Coordinates $start,
        private Coordinates $end,
        private int $capacity,
        private Modality $modality,
    ) {
        $this->id = $id;
        $this->registrations = new ArrayCollection();
    }

    public function id(): SegmentId
    {
        return SegmentId::from($this->id);
    }

    public function route(): Route
    {
        return $this->route;
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

    /**
     * @return array<int, Participant>
     */
    public function participants(): array
    {
        return array_map(
            fn (Registration $registration) => $registration->participant(),
            $this->registrations->toArray()
        );
    }

    public function isFull(): bool
    {
        return $this->capacity === $this->registrations->count();
    }
}
