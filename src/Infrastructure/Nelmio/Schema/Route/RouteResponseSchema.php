<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Route;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
        new OA\Property(property: 'name', type: 'string', example: 'Correllengua 2025'),
        new OA\Property(
            property: 'itineraries',
            type: 'array',
            items: new OA\Items(ref: new Model(type: ItineraryResponseSchema::class)),
        ),
    ],
    type: 'object',
)]
final class RouteResponseSchema
{
}
