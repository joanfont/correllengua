<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['name', 'position'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Costa Brava'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
    ],
    type: 'object',
)]
final class UpdateItineraryRequestSchema
{
}
