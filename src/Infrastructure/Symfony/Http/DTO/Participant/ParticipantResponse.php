<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Participant;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890'),
        new OA\Property(property: 'name', type: 'string', example: 'John'),
        new OA\Property(property: 'surname', type: 'string', example: 'Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
    ],
    type: 'object',
)]
final readonly class ParticipantResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $surname,
        public string $email,
    ) {
    }
}
