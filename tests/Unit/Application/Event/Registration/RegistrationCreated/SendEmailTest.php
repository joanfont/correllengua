<?php

namespace App\Tests\Unit\Application\Event\Registration\RegistrationCreated;

use App\Application\Event\Registration\RegistrationCreated\SendEmail;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Registration\Registration as RegistrationDTO;
use App\Domain\DTO\Participant\Participant as ParticipantDTO;
use App\Domain\DTO\Route\Segment as SegmentDTO;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Provider\Registration\RegistrationProvider;
use App\Domain\Event\Registration\RegistrationCreated;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SendEmailTest extends TestCase
{
    private RegistrationProvider&MockObject $provider;
    private RegistrationCreatedNotification&MockObject $notification;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(RegistrationProvider::class);
        $this->notification = $this->createMock(RegistrationCreatedNotification::class);

        self::set(RegistrationProvider::class, $this->provider);
        self::set(RegistrationCreatedNotification::class, $this->notification);
    }

    public function testInvokeFetchesRegistrationAndSendsNotification(): void
    {
        $registrationId = (string) RegistrationId::generate();
        $participantId = (string) ParticipantId::generate();
        $segmentId = (string) SegmentId::generate();

        $participant = new ParticipantDTO(
            id: $participantId,
            name: 'John',
            surname: 'Doe',
            email: 'john@example.com'
        );

        $segment = new SegmentDTO(
            id: $segmentId,
            start: new Coordinates(0.0, 0.0),
            end: new Coordinates(1.0, 1.0),
            capacity: 100,
            modality: 'road'
        );

        $registrationDto = new RegistrationDTO(
            id: $registrationId,
            modality: 'WALK',
            participant: $participant,
            segment: $segment,
        );

        $this->provider
            ->expects($this->once())
            ->method('findById')
            ->with($registrationId)
            ->willReturn($registrationDto);

        $this->notification
            ->expects($this->once())
            ->method('send')
            ->with($registrationDto);

        $handler = new SendEmail($this->provider, $this->notification);

        $event = new RegistrationCreated(
            RegistrationId::from($registrationId),
            ParticipantId::from($participantId),
            SegmentId::from($segmentId),
        );

        $handler($event);
    }
}

