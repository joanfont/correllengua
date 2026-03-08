<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['position', 'startLatitude', 'startLongitude', 'endLatitude', 'endLongitude', 'modality', 'startTime'],
    properties: [
        new OA\Property(property: 'position', type: 'integer', example: 1),
        new OA\Property(property: 'startLatitude', type: 'number', format: 'float', example: 41.3851),
        new OA\Property(property: 'startLongitude', type: 'number', format: 'float', example: 2.1734),
        new OA\Property(property: 'endLatitude', type: 'number', format: 'float', example: 41.9794),
        new OA\Property(property: 'endLongitude', type: 'number', format: 'float', example: 2.8214),
        new OA\Property(property: 'capacity', type: 'integer', example: 100, nullable: true),
        new OA\Property(property: 'modality', type: 'string', enum: ['WALK', 'BIKE', 'MIXED', 'END'], example: 'WALK'),
        new OA\Property(property: 'startTime', type: 'string', format: 'date-time', example: '2026-04-25T09:00:00+02:00'),
    ],
    type: 'object',
)]
final class UpdateSegmentRequestSchema
{
}
