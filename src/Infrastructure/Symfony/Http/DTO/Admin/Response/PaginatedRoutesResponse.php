<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Response;

use App\Domain\DTO\Admin\Route\AdminRoute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminRoute::class)),
        ),
        new OA\Property(property: 'total', type: 'integer', example: 5),
        new OA\Property(property: 'nextCursor', type: 'string', nullable: true, example: 'dXVpZC12YWx1ZQ=='),
    ],
    type: 'object',
)]
final readonly class PaginatedRoutesResponse
{
    /**
     * @param array<AdminRoute> $items
     */
    public function __construct(
        public array $items,
        public int $total,
        public ?string $nextCursor,
    ) {
    }
}
