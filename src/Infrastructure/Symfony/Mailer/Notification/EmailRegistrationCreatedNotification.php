<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Mailer\Notification;

use App\Application\Service\File\Filesystem;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Domain\DTO\Participant\Participant;
use App\Domain\DTO\Registration\Registration;

use function implode;
use function sprintf;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class EmailRegistrationCreatedNotification implements RegistrationCreatedNotification
{
    private const DEREGISTER_URL = 'https://correllenguaagermanat.cat/reserva/cancelacio?codi=%s';
    private const DEREGISTER_ALL_URL = 'https://correllenguaagermanat.cat/reserva/cancelacio?codis=%s';

    private readonly Environment $twig;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $from,
        private readonly Filesystem $filesystem,
        private readonly string $templatePath,
    ) {
        $this->twig = new Environment(new ArrayLoader());
    }

    /**
     * @param array<Registration> $registrations
     */
    public function send(array $registrations): void
    {
        if ([] === $registrations) {
            return;
        }

        $participant = $registrations[0]->participant;
        $to = sprintf(
            '%s %s <%s>',
            $participant->name,
            $participant->surname,
            $participant->email,
        );

        $templateContents = $this->filesystem->read($this->templatePath);
        $template = $this->twig->createTemplate($templateContents);

        $templateContext = $this->buildContext($participant, $registrations);
        $renderedTemplate = $template->render($templateContext);

        $email = new Email();
        $email
            ->from($this->from)
            ->subject('Confirmació de reserva - Correllengua Agermanat')
            ->to($to)
            ->html($renderedTemplate);

        $this->mailer->send($email);
    }

    /**
     * @param array<Registration> $registrations
     *
     * @return array<string, mixed>
     */
    private function buildContext(Participant $participant, array $registrations): array
    {
        $segments = [];
        $hashes = [];
        foreach ($registrations as $registration) {
            $segment = $registration->segment;
            $hashes[] = $registration->hash;
            $segments[] = [
                'date' => $segment->routeDate?->format('d/m/Y'),
                'time' => $segment->startTime?->format('H:i'),
                'itinerary' => $segment->itineraryName,
                'position' => $segment->position,
                'modality' => $segment->modality,
                'hash' => $registration->hash,
                'deregisterLink' => sprintf(self::DEREGISTER_URL, $registration->hash),
            ];
        }

        return [
            'participant' => [
                'name' => $participant->name,
                'surname' => $participant->surname,
            ],
            'segments' => $segments,
            'deregisterAllLink' => sprintf(self::DEREGISTER_ALL_URL, implode(',', $hashes)),
        ];
    }
}
