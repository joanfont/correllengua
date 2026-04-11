<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
        new OA\Property(
            property: 'start',
            type: 'object',
            properties: [
                new OA\Property(property: 'latitude', type: 'number', format: 'float', example: 41.3851),
                new OA\Property(property: 'longitude', type: 'number', format: 'float', example: 2.1734),
            ],
        ),
        new OA\Property(
            property: 'end',
            type: 'object',
            properties: [
                new OA\Property(property: 'latitude', type: 'number', format: 'float', example: 41.9794),
                new OA\Property(property: 'longitude', type: 'number', format: 'float', example: 2.8214),
            ],
        ),
        new OA\Property(property: 'capacity', type: 'integer', example: 100, nullable: true),
        new OA\Property(property: 'reservedCapacity', type: 'integer', example: 10, nullable: true),
        new OA\Property(property: 'modality', type: 'string', enum: ['WALK', 'BIKE', 'MIXED', 'END'], example: 'WALK'),
        new OA\Property(property: 'startTime', type: 'string', format: 'time', example: '09:00'),
        new OA\Property(property: 'itineraryId', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'itineraryName', type: 'string', example: 'Costa Brava'),
        new OA\Property(property: 'routeId', type: 'string', format: 'uuid', example: '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
        new OA\Property(property: 'routeName', type: 'string', example: 'Correllengua 2025'),
        new OA\Property(property: 'enrolments', type: 'integer', example: 78),
    ],
    type: 'object',
)]
final class AdminSegmentSchema
{
}
