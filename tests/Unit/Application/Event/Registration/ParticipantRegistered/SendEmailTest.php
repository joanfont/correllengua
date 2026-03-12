<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Registration\ParticipantRegistered;

use App\Application\Event\Registration\ParticipantRegistered\SendEmail;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Participant\Participant as ParticipantDTO;
use App\Domain\DTO\Registration\Registration as RegistrationDTO;
use App\Domain\DTO\Route\Segment as SegmentDTO;
use App\Domain\Event\Registration\ParticipantRegistered;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Provider\Registration\RegistrationProvider;
use App\Tests\TestCase;
use DateTimeImmutable;
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

    public function testInvokeFetchesRegistrationsAndSendsNotification(): void
    {
        $participantId = ParticipantId::generate();

        $participant = new ParticipantDTO(
            id: (string) $participantId,
            name: 'John',
            surname: 'Doe',
            email: 'john@example.com',
        );

        $segment1 = new SegmentDTO(
            id: (string) SegmentId::generate(),
            start: new Coordinates(0.0, 0.0),
            end: new Coordinates(1.0, 1.0),
            capacity: 100,
            modality: 'road',
            position: 1,
            itineraryName: 'Itinerary 1',
            routeDate: new DateTimeImmutable('2026-03-15'),
            startTime: new DateTimeImmutable('10:00:00'),
        );

        $segment2 = new SegmentDTO(
            id: (string) SegmentId::generate(),
            start: new Coordinates(1.0, 1.0),
            end: new Coordinates(2.0, 2.0),
            capacity: 50,
            modality: 'running',
            position: 2,
            itineraryName: 'Itinerary 1',
            routeDate: new DateTimeImmutable('2026-03-15'),
            startTime: new DateTimeImmutable('11:00:00'),
        );

        $registrations = [
            new RegistrationDTO(
                id: (string) RegistrationId::generate(),
                participant: $participant,
                segment: $segment1,
                hash: 'hash-1',
            ),
            new RegistrationDTO(
                id: (string) RegistrationId::generate(),
                participant: $participant,
                segment: $segment2,
                hash: 'hash-2',
            ),
        ];

        $this->provider
            ->expects($this->once())
            ->method('findByParticipantId')
            ->with((string) $participantId)
            ->willReturn($registrations);

        $this->notification
            ->expects($this->once())
            ->method('send')
            ->with($registrations);

        $handler = new SendEmail($this->provider, $this->notification);

        $event = new ParticipantRegistered($participantId);

        $handler($event);
    }

    public function testDoesNotSendNotificationWhenNoRegistrations(): void
    {
        $participantId = ParticipantId::generate();

        $this->provider
            ->expects($this->once())
            ->method('findByParticipantId')
            ->with((string) $participantId)
            ->willReturn([]);

        $this->notification
            ->expects($this->never())
            ->method('send');

        $handler = new SendEmail($this->provider, $this->notification);

        $event = new ParticipantRegistered($participantId);

        $handler($event);
    }
}
