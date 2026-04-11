<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
        new OA\Property(property: 'name', type: 'string', example: 'Correllengua 2025'),
        new OA\Property(property: 'description', type: 'string', example: 'Annual language run across Catalonia'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
        new OA\Property(property: 'startsAt', type: 'string', format: 'date-time', example: '2025-04-26T09:00:00+02:00'),
        new OA\Property(property: 'enrolments', type: 'integer', example: 120),
    ],
    type: 'object',
)]
final class AdminRouteSchema
{
}
