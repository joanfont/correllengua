<?php

declare(strict_types=1);

namespace App\Domain\Model\Route;

use App\Domain\Model\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Itinerary extends Entity
{
    private string $id;

    /** @var Collection<int, Segment> */
    private Collection $segments;

    public function __construct(
        ItineraryId $id,
        private Route $route,
        private string $name,
        private int $position,
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

    public function position(): int
    {
        return $this->position;
    }

    public function route(): Route
    {
        return $this->route;
    }

    /**
     * @return array<Segment>
     */
    public function segments(): array
    {
        return $this->segments->toArray();
    }
}
