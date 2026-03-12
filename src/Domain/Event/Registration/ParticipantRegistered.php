<?php

declare(strict_types=1);

namespace App\Domain\Event\Registration;

use App\Application\Commons\Event\Event;
use App\Domain\Model\Participant\ParticipantId;

readonly class ParticipantRegistered implements Event
{
    public function __construct(
        public ParticipantId $participantId,
    ) {
    }
}
