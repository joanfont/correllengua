<?php

namespace App\Domain\Model;

class Coordinates
{
    public function __construct(
        private float $latitude,
        private float $longitude,
    ) {}

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }
}
