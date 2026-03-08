<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminRouteSchema::class)),
        ),
        new OA\Property(property: 'total', type: 'integer', example: 5),
        new OA\Property(property: 'nextCursor', type: 'string', nullable: true, example: 'dXVpZC12YWx1ZQ=='),
    ],
    type: 'object',
)]
final class PaginatedRoutesSchema
{
}
