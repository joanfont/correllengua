<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Registration;

readonly class RegisterParticipantRequest
{
    /**
     * @param array<string> $segments
     */
    public function __construct(
        public array $segments,
        public Participant $participant,
    ) {
    }
}
