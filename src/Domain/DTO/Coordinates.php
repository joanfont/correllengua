<?php

namespace App\Domain\DTO;

readonly class Coordinates
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
    }
}