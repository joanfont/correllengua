<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Response;

use App\Domain\DTO\Admin\Route\AdminItinerary;
use App\Infrastructure\Symfony\Http\DTO\Common\CursorResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminItinerary::class)),
        ),
        new OA\Property(property: 'cursor', ref: new Model(type: CursorResponse::class)),
        new OA\Property(property: 'total', type: 'integer', example: 10),
    ],
    type: 'object',
)]
final readonly class PaginatedItinerariesResponse
{
    /**
     * @param array<AdminItinerary> $items
     */
    public function __construct(
        public array $items,
        public CursorResponse $cursor,
        public int $total,
    ) {
    }
}
