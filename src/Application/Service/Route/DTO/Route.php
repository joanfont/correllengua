<?php

namespace App\Application\Service\Route\DTO;

use DateTimeInterface;

readonly class Route
{
    public function __construct(
        public string $name,
        public string $description,
        public DateTimeInterface $startDate,
    ) {
    }
}
