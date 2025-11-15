<?php

namespace App\Domain\Model\Participant;

use App\Domain\Model\Entity;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Route\Segment;
use Doctrine\Common\Collections\Collection;

class Participant extends Entity
{
    private string $id;

    /** @var Collection<Registration> */
    private Collection $registrations;

    public function __construct(
        ParticipantId $id,
        private string $name,
        private string $surname,
        private string $email,
    ) {
        $this->id = (string) $id;
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

    /**
     * @return array<int, Segment>
     */
    public function segments(): array
    {
        return array_map(
            fn (Registration $registration) => $registration->segment(),
            $this->registrations->toArray()
        );
    }
}
