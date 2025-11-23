<?php

namespace App\Domain\Model\Registration;

use App\Domain\Event\Registration\RegistrationCreated;
use App\Domain\Model\Entity;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;

class Registration extends Entity
{
    private string $id;

    public function __construct(
        RegistrationId $id,
        private Participant $participant,
        private Segment $segment,
        private Modality $modality,
        private string $hash,
    ) {
        $this->id = (string) $id;

        $this->addEvent(RegistrationCreated::fromRegistration($this));
    }

    public function id(): RegistrationId
    {
        return RegistrationId::from($this->id);
    }

    public function participant(): Participant
    {
        return $this->participant;
    }

    public function segment(): Segment
    {
        return $this->segment;
    }

    public function modality(): Modality
    {
        return $this->modality;
    }

    public function hash(): string
    {
        return $this->hash;
    }
}
