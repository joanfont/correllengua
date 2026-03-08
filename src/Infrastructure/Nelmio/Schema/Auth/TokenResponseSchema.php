<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Auth;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'tokenType', type: 'string', example: 'basic'),
        new OA\Property(property: 'token', type: 'string', example: 'YWRtaW5AY29ycmVsbGVuZ3VhLmNhdDpzZWNyZXQ='),
    ],
    type: 'object',
)]
final class TokenResponseSchema
{
}
