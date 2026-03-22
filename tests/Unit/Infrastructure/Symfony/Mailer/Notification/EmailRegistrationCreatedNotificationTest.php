<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Symfony\Mailer\Notification;

use App\Application\Service\File\Filesystem;
use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Participant\Participant as ParticipantDTO;
use App\Domain\DTO\Registration\Registration as RegistrationDTO;
use App\Domain\DTO\Route\Segment as SegmentDTO;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Model\Route\SegmentId;
use App\Infrastructure\Symfony\Mailer\Notification\EmailRegistrationCreatedNotification;
use App\Tests\TestCase;
use DateTimeImmutable;

use function method_exists;

use PHPUnit\Framework\MockObject\MockObject;

use function quoted_printable_decode;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailRegistrationCreatedNotificationTest extends TestCase
{
    private MailerInterface&MockObject $mailer;

    private Filesystem&MockObject $filesystem;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->filesystem = $this->createMock(Filesystem::class);
    }

    public function testSendRendersTemplateAndSendsEmail(): void
    {
        $registrationId = (string) RegistrationId::generate();
        $participantId = (string) ParticipantId::generate();
        $segmentId = (string) SegmentId::generate();

        $participant = new ParticipantDTO(
            id: $participantId,
            name: 'John',
            surname: 'Doe',
            email: 'john@example.com',
        );

        $segment = new SegmentDTO(
            id: $segmentId,
            start: new Coordinates(0.0, 0.0),
            end: new Coordinates(1.0, 1.0),
            capacity: 100,
            modality: 'road',
            position: 1,
            itineraryName: 'Test Itinerary',
            routeName: 'Test Route',
            routeDate: new DateTimeImmutable('2026-03-15'),
            startTime: new DateTimeImmutable('10:30:00'),
        );

        $registration = new RegistrationDTO(
            id: $registrationId,
            participant: $participant,
            segment: $segment,
            hash: 'the-hash',
        );

        $templatePath = '/templates/registrationCreated.html.twig';
        $templateContents = 'Hello {{ participant.name }} {{ participant.surname }} - {% for s in segments %}{{ s.itinerary }} {{ s.hash }} {{ s.deregisterLink }}{% endfor %}';

        $this->filesystem
            ->expects(static::once())
            ->method('read')
            ->with($templatePath)
            ->willReturn($templateContents);

        $this->mailer
            ->expects(static::once())
            ->method('send')
            ->with($this->callback(function ($email) use ($participant): true {
                self::assertInstanceOf(Email::class, $email);

                $subject = $email->getSubject();
                self::assertSame('Confirmació de reserva - Correllengua Agermanat', $subject);

                $tos = $email->getTo();
                self::assertNotEmpty($tos);
                self::assertStringContainsString($participant->email, $tos[0]->getAddress());

                $body = method_exists($email, 'getHtmlBody') ? $email->getHtmlBody() : $email->toString();

                $decodedBody = quoted_printable_decode((string) $body);

                self::assertStringContainsString($participant->name, $decodedBody);
                self::assertStringContainsString($participant->surname, $decodedBody);
                self::assertStringContainsString('Test Itinerary', $decodedBody);
                self::assertStringContainsString('the-hash', $decodedBody);
                self::assertStringContainsString('https://correllenguaagermanat.cat/reserva/cancelacio?codi=the-hash', $decodedBody);

                return true;
            }));

        $notification = new EmailRegistrationCreatedNotification(
            $this->mailer,
            'from@example.com',
            $this->filesystem,
            $templatePath,
        );

        $notification->send([$registration]);
    }
}
