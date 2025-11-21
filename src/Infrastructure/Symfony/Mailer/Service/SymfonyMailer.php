<?php

namespace App\Infrastructure\Symfony\Mailer\Service;

use App\Application\Service\Email\Email;
use App\Application\Service\Email\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;

class SymfonyMailer implements Mailer
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $from,
    ) {
    }

    public function send(Email $email): void
    {
        $symfonyEmail = new SymfonyEmail();
        $symfonyEmail
            ->from($this->from)
            ->to($email->to)
            ->subject($email->subject)
            ->html($email->body);

        $this->mailer->send($symfonyEmail);
    }
}