<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Auth\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'token_type', type: 'string', example: 'basic'),
        new OA\Property(property: 'token', type: 'string', example: 'YWRtaW5AY29ycmVsbGVuZ3VhLmNhdDpzZWNyZXQ='),
    ],
    type: 'object',
)]
final readonly class TokenResponse
{
    public function __construct(
        public string $token_type,
        public string $token,
    ) {
    }
}

