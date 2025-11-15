<?php

namespace App\Domain\Model\Registration;

use App\Domain\Model\Entity;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\TransportMode;

class Registration extends Entity
{
    private string $id;

    public function __construct(
        RegistrationId $id,
        private Participant $participant,
        private Segment $segment,
        private TransportMode $transportMode,
    ) {
        $this->id = (string) $id;
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

    public function transportMode(): TransportMode
    {
        return $this->transportMode;
    }
}
