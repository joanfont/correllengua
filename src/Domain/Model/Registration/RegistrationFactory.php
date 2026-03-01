<?php

declare(strict_types=1);

namespace App\Domain\Model\Registration;

use App\Application\Service\Registration\RegistrationHasher;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Route\Segment;

class RegistrationFactory
{
    public function __construct(private readonly RegistrationHasher $registrationHasher)
    {
    }

    public function make(Participant $participant, Segment $segment): Registration
    {
        $id = RegistrationId::generate();
        $hash = $this->registrationHasher->hash($id);

        return new Registration(
            id: $id,
            participant: $participant,
            segment: $segment,
            hash: $hash,
        );
    }
}
