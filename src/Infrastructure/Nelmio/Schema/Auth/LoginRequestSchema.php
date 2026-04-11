<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Auth;

use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@correllengua.cat'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret'),
    ],
    type: 'object',
)]
final class LoginRequestSchema
{
}
