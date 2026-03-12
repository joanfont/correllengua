<?php

declare(strict_types=1);

namespace App\Domain\DTO\Registration;

use App\Domain\DTO\Participant\Participant;
use App\Domain\DTO\Route\Segment;

readonly class Registration
{
    public function __construct(
        public string $id,
        public Participant $participant,
        public Segment $segment,
        public ?string $hash = null,
    ) {
    }
}
