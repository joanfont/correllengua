<?php

namespace App\Infrastructure\Symfony\Http\DTO\Route;

readonly class RegisterParticipantRequest
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public string $modality,
    ) {
    }
}
