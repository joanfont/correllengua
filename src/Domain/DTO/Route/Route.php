<?php

namespace App\Domain\DTO\Route;

use App\Domain\Model\Route\Itinerary;

readonly class Route
{
    public function __construct(
        public string $id,
        public string $name,
        /** @var array<int, Itinerary> */
        public array $itineraries,
    ) {
    }
}
