<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Registration;

readonly class Participant
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
    ) {
    }
}
