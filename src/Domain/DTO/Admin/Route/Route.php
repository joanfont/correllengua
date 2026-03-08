<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Route;

readonly class Route
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public int $position,
        public string $startsAt,
        public int $enrolments,
    ) {
    }
}
