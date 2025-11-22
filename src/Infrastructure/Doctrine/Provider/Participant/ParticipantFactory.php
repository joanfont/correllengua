<?php

namespace App\Infrastructure\Doctrine\Provider\Participant;

use App\Domain\DTO\Participant\Participant;
use App\Domain\Model\Participant\Participant as ParticipantEntity;

class ParticipantFactory
{
    public function fromEntity(ParticipantEntity $participant): Participant
    {
        return new Participant(
            id: (string) $participant->id(),
            name: $participant->name(),
            surname: $participant->surname(),
            email: $participant->email(),
        );
    }
}
