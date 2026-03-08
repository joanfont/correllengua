<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'segmentId', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'segmentName', type: 'string', example: 'Tram 3'),
        new OA\Property(property: 'itineraryId', type: 'string', format: 'uuid', example: '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
        new OA\Property(property: 'itineraryName', type: 'string', example: 'Itinerari A – Costa Brava'),
        new OA\Property(property: 'routeId', type: 'string', format: 'uuid', example: 'a3bb189e-8bf9-3888-9912-ace4e6543002'),
        new OA\Property(property: 'routeName', type: 'string', example: 'Correllengua Litoral 2026'),
        new OA\Property(property: 'modality', type: 'string', enum: ['WALK', 'BIKE', 'MIXED', 'END'], example: 'WALK'),
        new OA\Property(property: 'hash', type: 'string', example: 'a1b2c3d4e5f6'),
    ],
    type: 'object',
)]
final class AdminRegistrationSchema
{
}
