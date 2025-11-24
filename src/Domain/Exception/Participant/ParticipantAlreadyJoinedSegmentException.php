<?php

namespace App\Domain\Exception\Participant;

use App\Domain\Exception\Exception;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Route\Segment;

final class ParticipantAlreadyJoinedSegmentException extends Exception
{
    public static function fromParticipantAndSegment(Participant $participant, Segment $segment): self
    {
        return new self(
            \sprintf(
                'Participant with id = %s already joined segment with id = %s',
                $participant->id(),
                $segment->id(),
            ),
        );
    }
}
