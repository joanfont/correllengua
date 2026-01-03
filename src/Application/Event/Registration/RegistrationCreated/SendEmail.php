<?php

declare(strict_types=1);

namespace App\Application\Event\Registration\RegistrationCreated;

use App\Application\Commons\Event\EventHandler;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Domain\Event\Registration\RegistrationCreated;
use App\Domain\Provider\Registration\RegistrationProvider;

final readonly class SendEmail implements EventHandler
{
    public function __construct(
        private RegistrationProvider $registrationProvider,
        private RegistrationCreatedNotification $registrationCreatedNotification,
    ) {
    }

    public function __invoke(RegistrationCreated $registrationCreated): void
    {
        $registration = $this->registrationProvider->findById((string) $registrationCreated->id);

        $this->registrationCreatedNotification->send($registration);
    }
}
