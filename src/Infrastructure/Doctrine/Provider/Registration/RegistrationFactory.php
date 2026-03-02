<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Registration;

use App\Domain\DTO\Registration\Registration;
use App\Domain\Model\Registration\Registration as RegistrationEntity;
use App\Infrastructure\Doctrine\Provider\Participant\ParticipantFactory;
use App\Infrastructure\Doctrine\Provider\Route\SegmentFactory;

readonly class RegistrationFactory
{
    public function __construct(
        private ParticipantFactory $participantFactory,
        private SegmentFactory $segmentFactory,
    ) {
    }

    public function fromEntity(RegistrationEntity $registration): Registration
    {
        return new Registration(
            id: (string) $registration->id(),
            participant: $this->participantFactory->fromEntity($registration->participant()),
            segment: $this->segmentFactory->fromEntity($registration->segment()),
        );
    }
}
