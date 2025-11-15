<?php

namespace App\Domain\Exception\Participant;

use App\Domain\Exception\Exception;
use App\Domain\Model\Participant\Participant;

final class ParticipantReachedMaxSegmentsException extends Exception
{
    public static function fromParticipant(Participant $participant, int $maxRegistrationsPerParticipant): self
    {
        return new self(
            sprintf(
                'Participant with id = %s has reached the maximum segment registrations (%d)',
                $participant->id(),
                $maxRegistrationsPerParticipant
            )
        );
    }
}