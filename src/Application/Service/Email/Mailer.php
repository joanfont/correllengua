<?php

namespace App\Application\Service\Email;

interface Mailer
{
    public function send(Email $email): void;
}
