<?php

namespace App\Domain\DTO\Route;

readonly class Itinerary
{
    public function __construct(
        public string $id,
        public string $name,
        /** @var array<Segment> */
        public array $segments,
    ) {
    }
}
