<?php

declare(strict_types=1);

namespace App\Application\Service\Notification;

use App\Domain\DTO\Registration\Registration;

interface RegistrationCreatedNotification
{
    public function send(Registration $registration): void;
}
