<?php

namespace App\Domain\DTO;

readonly class Coordinates
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
    }
}
