<?php

namespace App\Domain\DTO\Route;

use App\Domain\DTO\Coordinates;

readonly class Segment
{
    public function __construct(
        public string $id,
        public Coordinates $start,
        public Coordinates $end,
        public int $capacity,
        public string $modality,
    ) {
    }
}
