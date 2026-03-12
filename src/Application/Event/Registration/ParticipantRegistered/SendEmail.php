<?php

declare(strict_types=1);

namespace App\Application\Event\Registration\ParticipantRegistered;

use App\Application\Commons\Event\EventHandler;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Domain\Event\Registration\ParticipantRegistered;
use App\Domain\Provider\Registration\RegistrationProvider;

final readonly class SendEmail implements EventHandler
{
    public function __construct(
        private RegistrationProvider $registrationProvider,
        private RegistrationCreatedNotification $registrationCreatedNotification,
    ) {
    }

    public function __invoke(ParticipantRegistered $participantRegistered): void
    {
        $registrations = $this->registrationProvider->findByParticipantId(
            (string) $participantRegistered->participantId
        );

        if ([] === $registrations) {
            return;
        }

        $this->registrationCreatedNotification->send($registrations);
    }
}
