<?php

declare(strict_types=1);

namespace App\Domain\DTO\Participant;

readonly class Participant
{
    public function __construct(
        public string $id,
        public string $name,
        public string $surname,
        public string $email,
    ) {
    }
}
