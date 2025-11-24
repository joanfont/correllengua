<?php

namespace App\Domain\DTO\Route;

readonly class Itinerary
{
    public function __construct(
        public string $id,
        public string $name,
        /** @var array<int, Segment> */
        public array $segments,
    ) {}
}
