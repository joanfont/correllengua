<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    required: ['itinerary_id', 'position', 'start_latitude', 'start_longitude', 'end_latitude', 'end_longitude', 'modality', 'start_time'],
    properties: [
        new OA\Property(property: 'itinerary_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
        new OA\Property(property: 'start_latitude', type: 'number', format: 'float', example: 41.3851),
        new OA\Property(property: 'start_longitude', type: 'number', format: 'float', example: 2.1734),
        new OA\Property(property: 'end_latitude', type: 'number', format: 'float', example: 41.9794),
        new OA\Property(property: 'end_longitude', type: 'number', format: 'float', example: 2.8214),
        new OA\Property(property: 'capacity', type: 'integer', example: 100, nullable: true),
        new OA\Property(property: 'modality', type: 'string', enum: ['WALK', 'BIKE', 'MIXED', 'END'], example: 'WALK'),
        new OA\Property(property: 'start_time', type: 'string', format: 'date-time', example: '2026-04-25T09:00:00+02:00'),
    ],
    type: 'object',
)]
final readonly class CreateSegmentRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $itinerary_id,
        #[Assert\Positive]
        public int $position,
        #[Assert\NotBlank]
        #[Assert\Range(min: -90, max: 90)]
        public float $start_latitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -180, max: 180)]
        public float $start_longitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -90, max: 90)]
        public float $end_latitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -180, max: 180)]
        public float $end_longitude,
        public ?int $capacity,
        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['WALK', 'BIKE', 'MIXED', 'END'])]
        public string $modality,
        #[Assert\NotBlank]
        public string $start_time,
    ) {
    }
}
