<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    required: ['routeId', 'name', 'position'],
    properties: [
        new OA\Property(property: 'routeId', type: 'string', format: 'uuid', example: '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
        new OA\Property(property: 'name', type: 'string', example: 'Costa Brava'),
        new OA\Property(property: 'position', type: 'integer', example: 1),
    ],
    type: 'object',
)]
final readonly class CreateItineraryRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $routeId,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $name,
        #[Assert\Positive]
        public int $position,
    ) {
    }
}
