<?php

namespace App\Domain\Event\Registration;

use App\Application\Commons\Event\Event;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Model\Route\SegmentId;

abstract readonly class RegistrationEvent implements Event
{
    final public function __construct(
        public RegistrationId $id,
        public ParticipantId $participantId,
        public SegmentId $segmentId,
    ) {
    }

    final public static function fromRegistration(Registration $registration): static
    {
        return new static(
            id: $registration->id(),
            participantId: $registration->participant()->id(),
            segmentId: $registration->segment()->id()
        );
    }
}
