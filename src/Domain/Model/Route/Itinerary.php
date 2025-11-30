<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Itinerary extends Entity
{
    private readonly string $id;

    /** @var Collection<int, Segment> */
    private readonly Collection $segments;

    public function __construct(
        ItineraryId $id,
        private readonly Route $route,
        private readonly string $name,
    ) {
        $this->id = (string) $id;
        $this->segments = new ArrayCollection();
    }

    public function id(): ItineraryId
    {
        return ItineraryId::from($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function route(): Route
    {
        return $this->route;
    }

    /**
     * @return array<int, Segment>
     */
    public function segments(): array
    {
        return $this->segments->toArray();
    }
}
