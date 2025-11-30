<?php

namespace App\Domain\Model\Participant;

use App\Domain\Model\Entity;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Route\Segment;

use function array_map;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Participant extends Entity
{
    private readonly string $id;

    /** @var Collection<Registration> */
    private readonly Collection $registrations;

    public function __construct(
        ParticipantId $id,
        private readonly string $name,
        private readonly string $surname,
        private readonly string $email,
    ) {
        $this->id = (string) $id;

        $this->registrations = new ArrayCollection();
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
            fn (Registration $registration): \App\Domain\Model\Route\Segment => $registration->segment(),
            $this->registrations->toArray(),
        );
    }

    public function hasJoinedSegment(Segment $segment): bool
    {
        return $this->registrations
            ->map(fn (Registration $registration): string => (string) $registration->segment()->id())
            ->contains((string) $segment->id());
    }

    public function hasReachedMaxSegments(int $maxSegments): bool
    {
        return $maxSegments === $this->registrations->count();
    }
}
