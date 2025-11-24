<?php

namespace App\Application\Command\Registration;

use App\Application\Commons\Command\Command;

readonly class DeregisterParticipant implements Command
{
    public function __construct(
        public string $hash,
    ) {}
}
