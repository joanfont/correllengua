<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Common;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'error', type: 'string', example: 'Invalid request'),
        new OA\Property(property: 'message', type: 'string', example: 'The segment is full'),
        new OA\Property(property: 'code', type: 'integer', example: 400),
    ],
    type: 'object',
)]
final readonly class ErrorResponse
{
    public function __construct(
        public string $error,
        public string $message,
        public int $code,
    ) {
    }
}
