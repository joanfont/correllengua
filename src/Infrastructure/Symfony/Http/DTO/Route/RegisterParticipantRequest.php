<?php

namespace App\Infrastructure\Symfony\Http\DTO\Route;


readonly class RegisterParticipantRequest
{
    public function __construct(
        public array $segments = [],
        public Participant $participant,
    ) {}
}
