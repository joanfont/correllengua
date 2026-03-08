<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Route;

readonly class Itinerary
{
    public function __construct(
        public string $id,
        public string $name,
        public int $position,
        public string $routeId,
        public string $routeName,
        public int $enrolments,
    ) {
    }
}
