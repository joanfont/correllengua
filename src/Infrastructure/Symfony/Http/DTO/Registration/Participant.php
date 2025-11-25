<?php

namespace App\Infrastructure\Symfony\Http\DTO\Registration;

use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['name', 'surname', 'email'],
    properties: [
        new OA\Property(property: 'name', type: 'string', minLength: 1, example: 'John'),
        new OA\Property(property: 'surname', type: 'string', minLength: 1, example: 'Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
    ],
)]
readonly class Participant
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
    ) {
    }
}
