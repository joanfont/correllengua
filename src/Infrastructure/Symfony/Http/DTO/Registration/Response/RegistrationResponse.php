<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Registration\Response;

use App\Infrastructure\Symfony\Http\DTO\Participant\ParticipantResponse as ParticipantSimpleResponse;
use App\Infrastructure\Symfony\Http\DTO\Route\Response\SegmentResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: 'r1a2b3c4-d5e6-7890-abcd-ef1234567890'),
        new OA\Property(property: 'modality', type: 'string', enum: ['WALK', 'BIKE'], example: 'WALK'),
        new OA\Property(property: 'participant', ref: new Model(type: ParticipantSimpleResponse::class)),
        new OA\Property(property: 'segment', ref: new Model(type: SegmentResponse::class)),
    ],
    type: 'object',
)]
final readonly class RegistrationResponse
{
    public function __construct(
        public string $id,
        public string $modality,
        public ParticipantSimpleResponse $participant,
        public SegmentResponse $segment,
    ) {
    }
}
