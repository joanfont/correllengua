<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Stats;

readonly class Stats
{
    /**
     * @param array<EnrolmentsPerDay> $enrolmentsPerDay
     */
    public function __construct(
        public int $enrolments,
        public ?int $totalCapacity,
        public array $enrolmentsPerDay,
    ) {
    }
}
