<?php

namespace App\Infrastructure\Symfony\Http\DTO\Registration;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['segments', 'participant', 'modality'],
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
            ref: new Model(type: Participant::class),
        ),
        new OA\Property(
            property: 'modality',
            type: 'string',
            enum: ['WALK', 'BIKE'],
            example: 'WALK',
        ),
    ],
)]
readonly class RegisterParticipantRequest
{
    public function __construct(
        public array $segments,
        public Participant $participant,
        public string $modality,
    ) {
    }
}
