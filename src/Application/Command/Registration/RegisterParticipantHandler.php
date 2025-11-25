<?php

namespace App\Application\Command\Registration;

use App\Application\Command\Registration\DTO\Participant as ParticipantDTO;
use App\Application\Commons\Command\CommandHandler;
use App\Domain\Exception\Participant\ParticipantAlreadyJoinedSegmentException;
use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Exception\Participant\ParticipantReachedMaxSegmentsException;
use App\Domain\Exception\Route\ModalityMismatchException;
use App\Domain\Exception\Route\SegmentIsFullException;
use App\Domain\Exception\Route\SegmentNotFoundException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\RegistrationFactory;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;
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
        $modality = Modality::from($registerParticipant->modality);
        $segments = $this->loadAndValidateSegments($registerParticipant->segments, $modality);
        $participant = $this->findOrCreateParticipant($registerParticipant->participant);

        $this->registerParticipantToSegments($participant, $segments, $modality);
    }

    /**
     * @param array<int, string> $segmentIds
     *
     * @throws ModalityMismatchException
     * @throws SegmentIsFullException
     * @throws SegmentNotFoundException
     *
     * @return array<int, Segment>
     */
    private function loadAndValidateSegments(array $segmentIds, Modality $modality): array
    {
        $segments = [];

        foreach ($segmentIds as $segmentIdString) {
            $segmentId = SegmentId::from($segmentIdString);
            $segment = $this->segmentRepository->findById($segmentId);

            $this->validateSegmentAvailability($segment);
            $this->validateSegmentModality($segment, $modality);

            $segments[] = $segment;
        }

        return $segments;
    }

    private function validateSegmentAvailability(Segment $segment): void
    {
        if ($segment->isFull()) {
            throw SegmentIsFullException::fromSegment($segment);
        }
    }

    private function validateSegmentModality(Segment $segment, Modality $modality): void
    {
        if (Modality::MIXED !== $segment->modality() && $modality !== $segment->modality()) {
            throw ModalityMismatchException::fromSegment($segment, $modality);
        }
    }

    /**
     * @param array<int, Segment> $segments
     *
     * @throws ParticipantAlreadyJoinedSegmentException
     * @throws ParticipantReachedMaxSegmentsException
     */
    private function registerParticipantToSegments(
        Participant $participant,
        array $segments,
        Modality $modality,
    ): void {
        foreach ($segments as $segment) {
            $this->validateParticipantCanJoinSegment($participant, $segment);
            $this->createRegistration($participant, $segment, $modality);
        }
    }

    private function validateParticipantCanJoinSegment(Participant $participant, Segment $segment): void
    {
        if ($participant->hasJoinedSegment($segment)) {
            throw ParticipantAlreadyJoinedSegmentException::fromParticipantAndSegment($participant, $segment);
        }

        if ($participant->hasReachedMaxSegments($this->maxSegmentsPerParticipant)) {
            throw ParticipantReachedMaxSegmentsException::fromParticipant($participant, $this->maxSegmentsPerParticipant);
        }
    }

    private function createRegistration(Participant $participant, Segment $segment, Modality $modality): void
    {
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
