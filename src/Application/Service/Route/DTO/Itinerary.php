<?php

declare(strict_types=1);

namespace App\Application\Service\Route\DTO;

readonly class Itinerary
{
    public function __construct(
        public string $routeName,
        public string $name,
        public int $position,
    ) {
    }
}
