<?php

namespace App\Application\Service\Route\DTO;

readonly class Segment
{
    public function __construct(
        public int    $routeCode,
        public int    $position,
        public float  $startLatitude,
        public float  $startLongitude,
        public float  $endLatitude,
        public float  $endLongitude,
        public string $modality,
        public int    $capacity,
    ) {
    }
}
