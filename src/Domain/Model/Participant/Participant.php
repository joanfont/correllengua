<?php

declare(strict_types=1);

namespace App\Domain\Model\Participant;

use App\Domain\Model\Entity;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Route\Segment;

use function array_map;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use function in_array;

class Participant extends Entity
{
    private readonly string $id;

    /** @var Collection<int, Registration> */
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
     * @return array<Segment>
     */
    public function segments(): array
    {
        /** @var array<Registration> $registrations */
        $registrations = $this->registrations->toArray();

        return array_map(
            fn (Registration $registration): Segment => $registration->segment(),
            $registrations,
        );
    }

    public function hasJoinedSegment(Segment $segment): bool
    {
        /** @var array<Registration> $registrations */
        $registrations = $this->registrations->toArray();

        $ids = array_map(
            fn (Registration $registration): string => (string) $registration->segment()->id(),
            $registrations,
        );

        return in_array((string) $segment->id(), $ids, true);
    }

    public function hasReachedMaxSegments(int $maxSegments): bool
    {
        return $maxSegments === $this->registrations->count();
    }
}
