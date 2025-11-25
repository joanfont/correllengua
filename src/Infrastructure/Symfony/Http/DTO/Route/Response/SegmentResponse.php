<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Route\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'name', type: 'string', example: 'Barcelona - Girona'),
        new OA\Property(property: 'order', type: 'integer', example: 1),
        new OA\Property(property: 'distance', type: 'number', format: 'float', example: 25.5),
        new OA\Property(property: 'modality', type: 'string', enum: ['walk', 'bike', 'mixed'], example: 'walk'),
        new OA\Property(property: 'capacity', type: 'integer', example: 100),
        new OA\Property(property: 'registrations', type: 'integer', example: 45),
    ],
    type: 'object',
)]
final readonly class SegmentResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public int $order,
        public float $distance,
        public string $modality,
        public int $capacity,
        public int $registrations,
    ) {}
}
