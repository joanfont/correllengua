<?php

declare(strict_types=1);

namespace App\Domain\Model\Route;

use App\Domain\Model\Entity;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Route extends Entity
{
    private string $id;

    /**
     * @var Collection<int, Itinerary>
     */
    private Collection $itineraries;

    public function __construct(
        RouteId $id,
        private string $name,
        private string $description,
        private int $position,
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

    public function position(): int
    {
        return $this->position;
    }

    public function startsAt(): DateTimeInterface
    {
        return $this->startsAt;
    }

    public function update(
        string $name,
        string $description,
        int $position,
        DateTimeInterface $startsAt,
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->position = $position;
        $this->startsAt = $startsAt;
    }

    /**
     * @return array<Itinerary>
     */
    public function itineraries(): array
    {
        return $this->itineraries->toArray();
    }
}
