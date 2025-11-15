<?php

namespace App\Tests\Application\Command\Registration;

use App\Application\Command\Registration\RegisterParticipant;
use App\Domain\Exception\Participant\ParticipantAlreadyJoinedSegmentException;
use App\Domain\Exception\Participant\ParticipantReachedMaxSegmentsException;
use App\Domain\Exception\Route\ModalityMismatchException;
use App\Domain\Exception\Route\SegmentIsFullException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Participant\ParticipantRepository;
use App\Domain\Repository\Route\SegmentRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class RegisterParticipantTest extends TestCase
{
    private readonly SegmentRepository&MockObject $segmentRepository;
    private readonly ParticipantRepository&MockObject $participantRepository;
    private readonly Segment&MockObject $segment;
    private readonly Participant&MockObject $participant;

    protected function setUp(): void
    {
        $this->segmentRepository = $this->createMock(SegmentRepository::class);
        $this->participantRepository = $this->createMock(ParticipantRepository::class);
        $this->segment = $this->createMock(Segment::class);
        $this->participant = $this->createMock(Participant::class);

        self::set(SegmentRepository::class, $this->segmentRepository);
        self::set(ParticipantRepository::class, $this->participantRepository);
    }

    public function testRegistersParticipantToSameModalitySegment(): void
    {
        $segmentId = SegmentId::generate();
        $participantId = ParticipantId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($segmentId)
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $this->segment
            ->method('modality')
            ->willReturn(Modality::WALK);

        $this->participantRepository
            ->expects($this->once())
            ->method('findById')
            ->with($participantId)
            ->willReturn($this->participant);

        $this->participant
            ->expects($this->once())
            ->method('hasJoinedSegment')
            ->with($this->segment)
            ->willReturn(false);

        $this->participant
            ->expects($this->once())
            ->method('hasReachedMaxSegments')
            ->with(5)
            ->willReturn(false);

        $this->participant
            ->expects($this->once())
            ->method('joinSegment')
            ->with($this->segment, Modality::WALK);

        $registerParticipant = new RegisterParticipant(
            participantId: (string) $participantId,
            segmentId: (string) $segmentId,
            modality: Modality::WALK->value,
        );

        self::handleCommand($registerParticipant);
    }

    public function testThrowsExceptionWhenSegmentIsFull(): void
    {
        $segmentId = SegmentId::generate();
        $participantId = ParticipantId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(true);

        $this->segment
            ->expects($this->never())
            ->method('modality');

        $this->participantRepository
            ->expects($this->never())
            ->method('findById');

        static::expectException(SegmentIsFullException::class);

        $registerParticipant = new RegisterParticipant(
            participantId: $participantId,
            segmentId: $segmentId,
            modality: Modality::WALK->value
        );

        self::handleCommand($registerParticipant);
    }

    public function testThrowsExceptionWhenModalityDoesNotMatch(): void
    {
        $segmentId = SegmentId::generate();
        $participantId = ParticipantId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $this->segment
            ->method('modality')
            ->willReturn(Modality::WALK);

        $this->participantRepository
            ->expects($this->never())
            ->method('findById');

        static::expectException(ModalityMismatchException::class);

        $registerParticipant = new RegisterParticipant(
            participantId: $participantId,
            segmentId: $segmentId,
            modality: Modality::BIKE->value
        );

        self::handleCommand($registerParticipant);
    }

    public function testThrowsExceptionWhenParticipantAlreadyJoinedSegment(): void
    {
        $segmentId = SegmentId::generate();
        $participantId = ParticipantId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $this->segment
            ->method('modality')
            ->willReturn(Modality::WALK);

        $this->participantRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->participant);

        $this->participant
            ->expects($this->once())
            ->method('hasJoinedSegment')
            ->with($this->segment)
            ->willReturn(true);

        $this->participant
            ->expects($this->never())
            ->method('hasReachedMaxSegments');

        $this->participant
            ->expects($this->never())
            ->method('joinSegment');

        static::expectException(ParticipantAlreadyJoinedSegmentException::class);

        $registerParticipant = new RegisterParticipant(
            participantId: $participantId,
            segmentId: $segmentId,
            modality: Modality::WALK->value
        );

        self::handleCommand($registerParticipant);
    }

    public function testThrowsExceptionWhenParticipantReachedMaxSegments(): void
    {
        $segmentId = SegmentId::generate();
        $participantId = ParticipantId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $this->segment
            ->method('modality')
            ->willReturn(Modality::WALK);

        $this->participantRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->participant);

        $this->participant
            ->expects($this->once())
            ->method('hasJoinedSegment')
            ->with($this->segment)
            ->willReturn(false);

        $this->participant
            ->expects($this->once())
            ->method('hasReachedMaxSegments')
            ->with(5)
            ->willReturn(true);

        $this->participant
            ->expects($this->never())
            ->method('joinSegment');

        static::expectException(ParticipantReachedMaxSegmentsException::class);

        $registerParticipant = new RegisterParticipant(
            participantId: $participantId,
            segmentId: $segmentId,
            modality: Modality::WALK->value
        );

        self::handleCommand($registerParticipant);
    }

    public function testAllowsRegistrationWhenSegmentModalityIsMixed(): void
    {
        $segmentId = SegmentId::generate();
        $participantId = ParticipantId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $this->segment
            ->method('modality')
            ->willReturn(Modality::MIXED);

        $this->participantRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->participant);

        $this->participant
            ->expects($this->once())
            ->method('hasJoinedSegment')
            ->with($this->segment)
            ->willReturn(false);

        $this->participant
            ->expects($this->once())
            ->method('hasReachedMaxSegments')
            ->with(5)
            ->willReturn(false);

        $this->participant
            ->expects($this->once())
            ->method('joinSegment')
            ->with($this->segment, Modality::WALK);

        $registerParticipant = new RegisterParticipant(
            participantId: $participantId,
            segmentId: $segmentId,
            modality: Modality::WALK->value
        );

        self::handleCommand($registerParticipant);
    }
}
