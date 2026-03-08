<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Registration;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['segments', 'participant'],
    properties: [
        new OA\Property(
            property: 'segments',
            type: 'array',
            items: new OA\Items(type: 'string', format: 'uuid'),
            maxItems: 5,
            minItems: 1,
            example: ['550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440001'],
        ),
        new OA\Property(
            property: 'participant',
            ref: new Model(type: RegistrationParticipantSchema::class),
        ),
    ],
)]
final class RegisterParticipantRequestSchema
{
}
