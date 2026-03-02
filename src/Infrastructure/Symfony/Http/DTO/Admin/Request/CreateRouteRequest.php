<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    required: ['name', 'description', 'position', 'starts_at'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Correllengua 2026'),
        new OA\Property(property: 'description', type: 'string', example: 'Annual language run across Catalonia'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
        new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', example: '2026-04-25T09:00:00+02:00'),
    ],
    type: 'object',
)]
final readonly class CreateRouteRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $name,
        #[Assert\NotBlank]
        public string $description,
        #[Assert\Positive]
        public int $position,
        #[Assert\NotBlank]
        public string $starts_at,
    ) {
    }
}
