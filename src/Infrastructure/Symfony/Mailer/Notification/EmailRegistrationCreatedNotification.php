<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Mailer\Notification;

use App\Application\Service\File\Filesystem;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Application\Service\Registration\RegistrationHasher;
use App\Application\Service\Url\UrlGenerator;
use App\Domain\DTO\Registration\Registration;
use App\Domain\Model\Registration\RegistrationId;

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
        private readonly RegistrationHasher $registrationHasher,
    ) {
        $this->twig = new Environment(new ArrayLoader());
    }

    public function send(Registration $registration): void
    {
        $to = sprintf(
            '%s %s <%s>',
            $registration->participant->name,
            $registration->participant->surname,
            $registration->participant->email,
        );

        $templateContents = $this->filesystem->read($this->templatePath);
        $template = $this->twig->createTemplate($templateContents);

        $templateContext = $this->buildContext($registration);
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
     * @return array<string, mixed>
     */
    private function buildContext(Registration $registration): array
    {
        $registrationHash = $this->registrationHasher->hash(RegistrationId::from($registration->id));

        return [
            'participant' => [
                'name' => sprintf('%s %s', $registration->participant->name, $registration->participant->surname),
            ],
            'segment' => [
                'id' => $registration->segment->id,
            ],
            'deregisterLink' => $this->urlGenerator->generate('deregister_participant', [
                'hash' => $registrationHash,
            ]),
        ];
    }
}
