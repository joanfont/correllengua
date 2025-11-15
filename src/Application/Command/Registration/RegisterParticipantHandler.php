<?php

namespace App\Application\Command\Registration;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Exception\Participant\ParticipantAlreadyJoinedSegmentException;
use App\Domain\Exception\Participant\ParticipantReachedMaxSegmentsException;
use App\Domain\Exception\Route\ModalityMismatchException;
use App\Domain\Exception\Route\SegmentIsFullException;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Participant\ParticipantRepository;
use App\Domain\Repository\Route\SegmentRepository;

readonly class RegisterParticipantHandler implements CommandHandler
{
    public function __construct(
        private SegmentRepository $segmentRepository,
        private ParticipantRepository $participantRepository,
        private int $maxSegmentsPerParticipant,
    ) {
    }

    public function __invoke(RegisterParticipant $registerParticipant): void
    {
        $segmentId = SegmentId::from($registerParticipant->segmentId);
        $segment = $this->segmentRepository->findById($segmentId);
        if ($segment->isFull()) {
            throw SegmentIsFullException::fromSegment($segment);
        }

        $modality = Modality::from($registerParticipant->modality);
        if (Modality::MIXED !== $segment->modality() && $modality !== $segment->modality()) {
            throw ModalityMismatchException::fromSegment($segment, $modality);
        }

        $participantId = ParticipantId::from($registerParticipant->participantId);
        $participant = $this->participantRepository->findById($participantId);
        if ($participant->hasJoinedSegment($segment)) {
            throw ParticipantAlreadyJoinedSegmentException::fromParticipantAndSegment($participant, $segment);
        }

        if ($participant->hasReachedMaxSegments($this->maxSegmentsPerParticipant)) {
            throw ParticipantReachedMaxSegmentsException::fromParticipant($participant, $this->maxSegmentsPerParticipant);
        }

        $participant->joinSegment($segment, $modality);
    }
}
