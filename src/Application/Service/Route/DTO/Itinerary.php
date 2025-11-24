<?php

namespace App\Application\Service\Route\DTO;

readonly class Itinerary
{
    public function __construct(
        public string $routeName,
        public string $name,
    ) {}
}
