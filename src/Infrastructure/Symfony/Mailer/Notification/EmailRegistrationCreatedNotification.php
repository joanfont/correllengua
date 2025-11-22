<?php

namespace App\Infrastructure\Symfony\Mailer\Notification;

use App\Application\Service\File\Filesystem;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Domain\DTO\Registration\Registration;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class EmailRegistrationCreatedNotification implements RegistrationCreatedNotification
{
    private Environment $twig;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $from,
        private readonly Filesystem $filesystem,
        private readonly string $templatePath,
    ) {
        $this->twig = new Environment(new ArrayLoader());
    }

    public function send(Registration $registration): void
    {
        $to = sprintf(
            '%s %s <%s>',
            $registration->participant->name,
            $registration->participant->surname,
            $registration->participant->email
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

    private function buildContext(Registration $registration): array
    {
        return [
            'participant' => [
                'name' => sprintf('%s %s', $registration->participant->name, $registration->participant->surname),
            ],
            'segment' => [
                'id' => $registration->segment->id,
            ],
            'cancelRegistrationLink' => 'https://ca.wikipedia.org/wiki/Correllengua',
        ];
    }
}
