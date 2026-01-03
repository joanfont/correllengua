<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Symfony\Mailer\Notification;

use App\Application\Service\File\Filesystem;
use App\Application\Service\Registration\RegistrationHasher;
use App\Application\Service\Url\UrlGenerator;
use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Participant\Participant as ParticipantDTO;
use App\Domain\DTO\Registration\Registration as RegistrationDTO;
use App\Domain\DTO\Route\Segment as SegmentDTO;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Model\Route\SegmentId;
use App\Infrastructure\Symfony\Mailer\Notification\EmailRegistrationCreatedNotification;
use App\Tests\TestCase;

use function method_exists;

use PHPUnit\Framework\MockObject\MockObject;

use function quoted_printable_decode;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailRegistrationCreatedNotificationTest extends TestCase
{
    private MailerInterface&MockObject $mailer;

    private Filesystem&MockObject $filesystem;

    private UrlGenerator&MockObject $urlGenerator;

    private RegistrationHasher&MockObject $registrationHasher;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->urlGenerator = $this->createMock(UrlGenerator::class);
        $this->registrationHasher = $this->createMock(RegistrationHasher::class);
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
        );

        $registration = new RegistrationDTO(
            id: $registrationId,
            modality: 'WALK',
            participant: $participant,
            segment: $segment,
        );

        $templatePath = '/templates/registrationCreated.html.twig';
        $templateContents = 'Hello {{ participant.name }} - segment {{ segment.id }} - {{ deregisterLink }}';

        $this->filesystem
            ->expects(static::once())
            ->method('read')
            ->with($templatePath)
            ->willReturn($templateContents);

        $this->registrationHasher
            ->expects(static::once())
            ->method('hash')
            ->willReturn('the-hash');

        $this->urlGenerator
            ->expects(static::once())
            ->method('generate')
            ->with('deregister_participant', ['hash' => 'the-hash'])
            ->willReturn('http://example/deregister');

        $this->mailer
            ->expects(static::once())
            ->method('send')
            ->with($this->callback(function ($email) use ($participant): true {
                self::assertInstanceOf(Email::class, $email);

                $subject = $email->getSubject();
                self::assertSame('Correllengua', $subject);

                $tos = $email->getTo();
                self::assertNotEmpty($tos);
                self::assertStringContainsString($participant->email, $tos[0]->getAddress());

                $body = method_exists($email, 'getHtmlBody') ? $email->getHtmlBody() : $email->toString();

                $decodedBody = quoted_printable_decode((string) $body);

                self::assertStringContainsString($participant->name.' '.$participant->surname, $decodedBody);
                self::assertStringContainsString('http://example/deregister', $decodedBody);

                return true;
            }));

        $notification = new EmailRegistrationCreatedNotification(
            $this->mailer,
            'from@example.com',
            $this->filesystem,
            $templatePath,
            $this->urlGenerator,
            $this->registrationHasher,
        );

        $notification->send($registration);
    }
}
