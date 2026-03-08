<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Route;

use App\Domain\DTO\Coordinates;

readonly class Segment
{
    public function __construct(
        public string $id,
        public int $position,
        public Coordinates $start,
        public Coordinates $end,
        public ?int $capacity,
        public string $modality,
        public string $startTime,
        public string $itineraryId,
        public string $itineraryName,
        public string $routeId,
        public string $routeName,
        public int $enrolments,
    ) {
    }
}
