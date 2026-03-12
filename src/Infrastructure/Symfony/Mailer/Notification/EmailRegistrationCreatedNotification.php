<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Mailer\Notification;

use App\Application\Service\File\Filesystem;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Application\Service\Url\UrlGenerator;
use App\Domain\DTO\Registration\Registration;

use function sprintf;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class EmailRegistrationCreatedNotification implements RegistrationCreatedNotification
{
    private readonly Environment $twig;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $from,
        private readonly Filesystem $filesystem,
        private readonly string $templatePath,
        private readonly UrlGenerator $urlGenerator,
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

        $firstRegistration = $registrations[0];
        $to = sprintf(
            '%s %s <%s>',
            $firstRegistration->participant->name,
            $firstRegistration->participant->surname,
            $firstRegistration->participant->email,
        );

        $templateContents = $this->filesystem->read($this->templatePath);
        $template = $this->twig->createTemplate($templateContents);

        $templateContext = $this->buildContext($registrations);
        $renderedTemplate = $template->render($templateContext);

        $email = new Email();
        $email
            ->from($this->from)
            ->subject('Correllengua')
            ->to($to)
            ->html($renderedTemplate);

        $this->mailer->send($email);
    }

    /**
     * @param array<Registration> $registrations
     *
     * @return array<string, mixed>
     */
    private function buildContext(array $registrations): array
    {
        $firstRegistration = $registrations[0];

        $segments = [];
        foreach ($registrations as $registration) {
            $segment = $registration->segment;
            $segments[] = [
                'date' => $segment->routeDate?->format('d/m/Y'),
                'time' => $segment->startTime?->format('H:i'),
                'itinerary' => $segment->itineraryName,
                'position' => $segment->position,
                'modality' => $segment->modality,
                'hash' => $registration->hash,
                'deregisterLink' => $this->urlGenerator->generate('deregister_participant', [
                    'hash' => $registration->hash,
                ]),
            ];
        }

        return [
            'participant' => [
                'name' => $firstRegistration->participant->name,
                'surname' => $firstRegistration->participant->surname,
            ],
            'segments' => $segments,
        ];
    }
}
