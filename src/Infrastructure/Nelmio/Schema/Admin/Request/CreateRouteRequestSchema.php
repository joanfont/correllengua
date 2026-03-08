<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['name', 'description', 'position', 'startsAt'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Correllengua 2026'),
        new OA\Property(property: 'description', type: 'string', example: 'Annual language run across Catalonia'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
        new OA\Property(property: 'startsAt', type: 'string', format: 'date-time', example: '2026-04-25T09:00:00+02:00'),
    ],
    type: 'object',
)]
final class CreateRouteRequestSchema
{
}
