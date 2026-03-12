<?php

declare(strict_types=1);

namespace App\Application\Service\Notification;

use App\Domain\DTO\Registration\Registration;

interface RegistrationCreatedNotification
{
    /**
     * @param array<Registration> $registrations
     */
    public function send(array $registrations): void;
}
