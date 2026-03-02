<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

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
final readonly class UpdateSegmentRequest
{
    public function __construct(
        #[Assert\Positive]
        public int $position,
        #[Assert\NotBlank]
        #[Assert\Range(min: -90, max: 90)]
        public float $startLatitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -180, max: 180)]
        public float $startLongitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -90, max: 90)]
        public float $endLatitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -180, max: 180)]
        public float $endLongitude,
        public ?int $capacity,
        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['WALK', 'BIKE', 'MIXED', 'END'])]
        public string $modality,
        #[Assert\NotBlank]
        public string $startTime,
    ) {
    }
}
