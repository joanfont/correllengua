<?php

namespace App\Application\Service\Route\DTO;

readonly class Route
{
    public function __construct(
        public int $code,
        public string $name,
        public string $description,
        public \DateTimeInterface $startDate,
    ) {
    }
}
