<?php

namespace App\Application\Service\Route\DTO;

readonly class Route
{
    public function __construct(
        public string $name,
        public string $description,
        public \DateTimeInterface $startDate,
    ) {}
}
