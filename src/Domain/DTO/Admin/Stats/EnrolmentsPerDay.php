<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Stats;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-04-25'),
        new OA\Property(property: 'count', type: 'integer', example: 42),
    ],
    type: 'object',
)]
readonly class EnrolmentsPerDay
{
    public function __construct(
        public string $date,
        public int $count,
    ) {
    }
}
