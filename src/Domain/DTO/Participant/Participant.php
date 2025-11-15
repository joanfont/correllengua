<?php

namespace App\Domain\DTO\Participant;

use App\Domain\Model\Route\Modality;

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