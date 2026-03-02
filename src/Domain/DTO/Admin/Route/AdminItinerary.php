<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Route;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'name', type: 'string', example: 'Costa Brava'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
        new OA\Property(property: 'routeId', type: 'string', format: 'uuid', example: '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
        new OA\Property(property: 'routeName', type: 'string', example: 'Correllengua 2025'),
    ],
    type: 'object',
)]
readonly class AdminItinerary
{
    public function __construct(
        public string $id,
        public string $name,
        public int $position,
        public string $routeId,
        public string $routeName,
    ) {
    }
}
