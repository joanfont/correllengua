<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Registration;

use App\Application\Command\Registration\DTO\Participant as ParticipantDTO;
use App\Application\Command\Registration\RegisterParticipant;
use App\Domain\Exception\Participant\ParticipantAlreadyJoinedSegmentException;
use App\Domain\Exception\Participant\ParticipantReachedMaxSegmentsException;
use App\Domain\Exception\Route\SegmentIsFullException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Registration\RegistrationFactory;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Participant\ParticipantRepository;
use App\Domain\Repository\Route\SegmentRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;

#[AllowMockObjectsWithoutExpectations]
class RegisterParticipantTest extends TestCase
{
    private readonly SegmentRepository&MockObject $segmentRepository;

    private readonly ParticipantRepository&MockObject $participantRepository;

    private readonly RegistrationFactory&MockObject $registrationFactory;

    private readonly Segment&MockObject $segment;

    private readonly Participant&MockObject $participant;

    protected function setUp(): void
    {
        $this->segmentRepository = $this->createMock(SegmentRepository::class);
        $this->participantRepository = $this->createMock(ParticipantRepository::class);
        $this->registrationFactory = $this->createMock(RegistrationFactory::class);
        $this->segment = $this->createMock(Segment::class);
        $this->participant = $this->createMock(Participant::class);

        self::set(SegmentRepository::class, $this->segmentRepository);
        self::set(ParticipantRepository::class, $this->participantRepository);
        self::set(RegistrationFactory::class, $this->registrationFactory);
    }

    public function testRegistersParticipantToSegment(): void
    {
        $segmentId = SegmentId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($segmentId)
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $participantEmail = 'john@example.com';
        $participantDto = new ParticipantDTO(
            name: 'John',
            surname: 'Doe',
            email: $participantEmail,
        );

        $this->participantRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($participantEmail)
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

        $registration = $this->createStub(Registration::class);

        $this->registrationFactory
            ->expects($this->once())
            ->method('make')
            ->with($this->participant, $this->segment)
            ->willReturn($registration);

        $this->segment
            ->expects($this->once())
            ->method('addRegistration')
            ->with($registration);

        $registerParticipant = new RegisterParticipant(
            $participantDto,
            [(string) $segmentId],
        );

        self::handleCommand($registerParticipant);
    }

    public function testThrowsExceptionWhenSegmentIsFull(): void
    {
        $segmentId = SegmentId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(true);

        $this->participantRepository
            ->expects($this->never())
            ->method('findByEmail');

        static::expectException(SegmentIsFullException::class);

        $participantDto = new ParticipantDTO(
            name: 'John',
            surname: 'Doe',
            email: 'john@example.com',
        );

        $registerParticipant = new RegisterParticipant(
            $participantDto,
            [(string) $segmentId],
        );

        self::handleCommand($registerParticipant);
    }

    public function testThrowsExceptionWhenParticipantAlreadyJoinedSegment(): void
    {
        $segmentId = SegmentId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $participantEmail = 'john@example.com';
        $participantDto = new ParticipantDTO(
            name: 'John',
            surname: 'Doe',
            email: $participantEmail,
        );

        $this->participantRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($participantEmail)
            ->willReturn($this->participant);

        $this->participant
            ->expects($this->once())
            ->method('hasJoinedSegment')
            ->with($this->segment)
            ->willReturn(true);

        $this->participant
            ->expects($this->never())
            ->method('hasReachedMaxSegments');

        static::expectException(ParticipantAlreadyJoinedSegmentException::class);

        $registerParticipant = new RegisterParticipant(
            $participantDto,
            [(string) $segmentId],
        );

        self::handleCommand($registerParticipant);
    }

    public function testThrowsExceptionWhenParticipantReachedMaxSegments(): void
    {
        $segmentId = SegmentId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($this->segment);

        $this->segment
            ->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $participantEmail = 'john@example.com';
        $participantDto = new ParticipantDTO(
            name: 'John',
            surname: 'Doe',
            email: $participantEmail,
        );

        $this->participantRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($participantEmail)
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

        static::expectException(ParticipantReachedMaxSegmentsException::class);

        $registerParticipant = new RegisterParticipant(
            $participantDto,
            [(string) $segmentId],
        );

        self::handleCommand($registerParticipant);
    }
}
