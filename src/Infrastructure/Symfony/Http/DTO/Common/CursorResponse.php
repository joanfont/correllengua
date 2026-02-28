<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Common;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'next', type: 'string', nullable: true, example: 'YTFiMmMzZDQtZTVmNi03ODkwLWFiY2QtZWYxMjM0NTY3ODkw'),
        new OA\Property(property: 'previous', type: 'string', nullable: true, example: null),
    ],
    type: 'object',
)]
final readonly class CursorResponse
{
    public function __construct(
        public ?string $next,
        public ?string $previous,
    ) {
    }
}
