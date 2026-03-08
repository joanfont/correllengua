<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Registration;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: 'r1a2b3c4-d5e6-7890-abcd-ef1234567890'),
        new OA\Property(property: 'modality', type: 'string', enum: ['WALK', 'BIKE'], example: 'WALK'),
        new OA\Property(
            property: 'participant',
            ref: new Model(type: \App\Infrastructure\Nelmio\Schema\Participant\ParticipantResponseSchema::class),
        ),
        new OA\Property(
            property: 'segment',
            ref: new Model(type: \App\Infrastructure\Nelmio\Schema\Route\SegmentResponseSchema::class),
        ),
    ],
    type: 'object',
)]
final class RegistrationResponseSchema
{
}
