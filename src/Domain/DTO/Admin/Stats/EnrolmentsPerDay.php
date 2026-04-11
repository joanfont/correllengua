<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Stats;

readonly class EnrolmentsPerDay
{
    public function __construct(
        public string $date,
        public int $count,
    ) {
    }
}
