<?php

namespace App\Domain\DTO\Registration;

use App\Domain\DTO\Participant\Participant;
use App\Domain\DTO\Route\Segment;

readonly class Registration
{
    public function __construct(
        public string $id,
        public string $modality,
        public Participant $participant,
        public Segment $segment,
    ) {
    }
}