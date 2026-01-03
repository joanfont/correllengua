<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Route\Response;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'name', type: 'string', example: 'Costa Brava Route'),
        new OA\Property(property: 'description', type: 'string', example: 'Beautiful coastal route through Catalonia'),
        new OA\Property(
            property: 'segments',
            type: 'array',
            items: new OA\Items(ref: new Model(type: SegmentResponse::class)),
        ),
    ],
    type: 'object',
)]
final readonly class ItineraryResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        /** @var array<SegmentResponse> */
        public array $segments,
    ) {
    }
}
