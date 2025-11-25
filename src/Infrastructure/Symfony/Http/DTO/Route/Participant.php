<?php

namespace App\Infrastructure\Symfony\Http\DTO\Route;

readonly class Participant
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
    ) {}
}
