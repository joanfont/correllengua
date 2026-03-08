<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-04-25'),
        new OA\Property(property: 'count', type: 'integer', example: 42),
    ],
    type: 'object',
)]
final class EnrolmentsPerDaySchema
{
}
