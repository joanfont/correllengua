<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Admin;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'name', type: 'string', example: 'Anna'),
        new OA\Property(property: 'surname', type: 'string', example: 'Puig'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'anna.puig@example.cat'),
        new OA\Property(
            property: 'registrations',
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminRegistrationSchema::class)),
        ),
    ],
    type: 'object',
)]
final class AdminParticipantSchema
{
}
