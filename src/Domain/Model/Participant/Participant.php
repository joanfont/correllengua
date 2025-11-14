<?php

namespace App\Domain\Model\Participant;

use App\Domain\Model\Entity;

class Participant extends Entity
{
    private string $id;

    public function __construct(
        ParticipantId  $id,
        private string $name,
        private string $surname,
        private string $email,
    )
    {
        $this->id = (string)$id;
    }

    public function id(): ParticipantId
    {
        return ParticipantId::from($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function surname(): string
    {
        return $this->surname;
    }

    public function email(): string
    {
        return $this->email;
    }
}