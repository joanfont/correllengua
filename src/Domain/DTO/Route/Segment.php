<?php

declare(strict_types=1);

namespace App\Domain\DTO\Route;

use App\Domain\DTO\Coordinates;
use DateTimeInterface;

readonly class Segment
{
    public function __construct(
        public string $id,
        public Coordinates $start,
        public Coordinates $end,
        public ?int $capacity,
        public string $modality,
        public ?int $position = null,
        public ?string $itineraryName = null,
        public ?string $routeName = null,
        public ?DateTimeInterface $routeDate = null,
        public ?DateTimeInterface $startTime = null,
    ) {
    }
}
