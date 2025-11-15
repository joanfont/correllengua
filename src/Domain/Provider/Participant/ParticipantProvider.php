<?php

namespace App\Domain\Provider\Participant;

use App\Domain\DTO\Participant\Participant;

interface ParticipantProvider
{
    public function findByEmail(string $email): Participant;
}