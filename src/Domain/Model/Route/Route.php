<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\Entity;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Route extends Entity
{
    private string $id;

    /**
     * @var Collection<Itinerary>
     */
    private Collection $itineraries;

    public function __construct(
        RouteId $id,
        private string $name,
        private string $description,
        private DateTimeInterface $startsAt,
    ) {
        $this->id = (string) $id;
        $this->itineraries = new ArrayCollection();
    }

    public function id(): RouteId
    {
        return RouteId::from($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function startsAt(): DateTimeInterface
    {
        return $this->startsAt;
    }

    /**
     * @return array<int, Itinerary>
     */
    public function itineraries(): array
    {
        return $this->itineraries->toArray();
    }
}
