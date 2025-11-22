<?php

namespace App\Application\Service\Email;

readonly class Email
{
    public function __construct(
        public string $to,
        public string $subject,
        public string $body,
    ) {
    }
}
