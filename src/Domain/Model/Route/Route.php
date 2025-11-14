<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Route extends Entity
{
    private string $id;

    /**
     * @var Collection<Segment>
     */
    private Collection $segments;

    public function __construct(
        RouteId $id,
        private string $name,
    ) {
        $this->id = (string) $id;
        $this->segments = new ArrayCollection();
    }

    public function id(): RouteId
    {
        return RouteId::from($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function segments(): array
    {
        return $this->segments->toArray();
    }

}