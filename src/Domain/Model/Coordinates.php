<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Coordinates
{
    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
    ) {
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }
}
