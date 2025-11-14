<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\Coordinates;
use App\Domain\Model\Entity;

class Segment extends Entity
{
    private string $id;

    private Route $route;

    public function __construct(
        SegmentId $id,
        private int $position,
        private Coordinates $start,
        private int $capacity,
        private TransportMode $transportMode,
    ) {
        $this->id = $id;
    }

    public function id(): SegmentId
    {
        return SegmentId::from($this->id);
    }

    public function position(): int
    {
        return $this->position;
    }

    public function start(): Coordinates
    {
        return $this->start;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }

    public function transportMode(): TransportMode
    {
        return $this->transportMode;
    }

    public function forRoute(Route $route): void
    {
        $this->route = $route;
    }
}
