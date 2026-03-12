<?php

declare(strict_types=1);

namespace App\Application\Command\Registration;

use App\Application\Command\Registration\DTO\Participant as ParticipantDTO;
use App\Application\Commons\Command\CommandHandler;
use App\Application\Commons\Event\EventPublisher;
use App\Domain\Event\Registration\ParticipantRegistered;
use App\Domain\Exception\Participant\ParticipantAlreadyJoinedSegmentException;
use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Exception\Participant\ParticipantReachedMaxSegmentsException;
use App\Domain\Exception\Route\SegmentIsFullException;
use App\Domain\Exception\Route\SegmentNotFoundException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\RegistrationFactory;
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
        private EventPublisher $eventPublisher,
    ) {
    }

    public function __invoke(RegisterParticipant $registerParticipant): void
    {
        /** @var array<string> $segmentIds */
        $segmentIds = $registerParticipant->segments;
        $segments = $this->loadAndValidateSegments($segmentIds);
        $participant = $this->findOrCreateParticipant($registerParticipant->participant);

        $this->registerParticipantToSegments($participant, $segments);

        $this->eventPublisher->publish(new ParticipantRegistered($participant->id()));
    }

    /**
     * @param array<string> $segmentIds
     *
     * @throws SegmentIsFullException
     * @throws SegmentNotFoundException
     *
     * @return array<Segment>
     */
    private function loadAndValidateSegments(array $segmentIds): array
    {
        $segments = [];

        foreach ($segmentIds as $segmentIdString) {
            $segmentId = SegmentId::from($segmentIdString);
            $segment = $this->segmentRepository->findById($segmentId);

            $this->validateSegmentAvailability($segment);

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

    /**
     * @param array<Segment> $segments
     *
     * @throws ParticipantAlreadyJoinedSegmentException
     * @throws ParticipantReachedMaxSegmentsException
     */
    private function registerParticipantToSegments(
        Participant $participant,
        array $segments,
    ): void {
        foreach ($segments as $segment) {
            $this->validateParticipantCanJoinSegment($participant, $segment);
            $this->createRegistration($participant, $segment);
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

    private function createRegistration(Participant $participant, Segment $segment): void
    {
        $registration = $this->registrationFactory->make($participant, $segment);
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
