<?php

declare(strict_types=1);

namespace App\Domain\Event\Registration;

use App\Application\Commons\Event\Event;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Route\SegmentId;

readonly class ParticipantJoinedSegments implements Event
{
    public function __construct(
        public ParticipantId $participantId,
        /**
         * @var array<SegmentId>
         */
        public array $segmentIds,
    ) {
    }
}
