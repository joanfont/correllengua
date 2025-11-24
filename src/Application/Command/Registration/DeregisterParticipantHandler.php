<?php

namespace App\Application\Command\Registration;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Repository\Registration\RegistrationRepository;

readonly class DeregisterParticipantHandler implements CommandHandler
{
    public function __construct(
        private RegistrationRepository $registrationRepository,
    ) {}

    public function __invoke(DeregisterParticipant $deregisterParticipant): void
    {
        $registration = $this->registrationRepository->findByHash($deregisterParticipant->hash);

        $this->registrationRepository->delete($registration);
    }
}
