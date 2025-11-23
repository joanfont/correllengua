<?php

namespace App\Application\Command\Registration;

use App\Application\Command\Registration\DTO\Participant as ParticipantDTO;
use App\Application\Commons\Command\CommandHandler;
use App\Domain\Exception\Participant\ParticipantAlreadyJoinedSegmentException;
use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Exception\Participant\ParticipantReachedMaxSegmentsException;
use App\Domain\Exception\Route\ModalityMismatchException;
use App\Domain\Exception\Route\SegmentIsFullException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\RegistrationFactory;
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
        private RegistrationFactory $registrationFactory,
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

        $participant = $this->findOrCreateParticipant($registerParticipant->participant);
        if ($participant->hasJoinedSegment($segment)) {
            throw ParticipantAlreadyJoinedSegmentException::fromParticipantAndSegment($participant, $segment);
        }

        if ($participant->hasReachedMaxSegments($this->maxSegmentsPerParticipant)) {
            throw ParticipantReachedMaxSegmentsException::fromParticipant($participant, $this->maxSegmentsPerParticipant);
        }

        $registration = $this->registrationFactory->make($participant, $segment, $modality);

        $segment->addRegistration($registration);
    }

    private function findOrCreateParticipant(ParticipantDTO $participant): Participant
    {
        try {
            $participant = $this->participantRepository->findByEmail($participant->email);
        } catch (ParticipantNotFoundException) {
            $participant = new Participant(
                id: ParticipantId::generate(),
                name: $participant->name,
                surname: $participant->surname,
                email: $participant->email,
            );

            $this->participantRepository->add($participant);
        }

        return $participant;
    }
}
