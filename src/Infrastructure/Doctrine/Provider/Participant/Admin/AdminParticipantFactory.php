<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Participant\Admin;

use App\Domain\DTO\Admin\Participant\Participant;
use App\Domain\DTO\Admin\Participant\Registration;
use App\Domain\Model\Participant\Participant as ParticipantEntity;
use App\Domain\Model\Registration\Registration as RegistrationEntity;

use function array_map;
use function sprintf;

readonly class AdminParticipantFactory
{
    public function fromEntity(ParticipantEntity $participant): Participant
    {
        $registrations = array_map(
            fn (RegistrationEntity $reg) => new Registration(
                id: (string) $reg->id(),
                segmentId: (string) $reg->segment()->id(),
                segmentName: sprintf('Tram %d', $reg->segment()->position()),
                itineraryId: (string) $reg->segment()->itinerary()->id(),
                itineraryName: $reg->segment()->itinerary()->name(),
                routeId: (string) $reg->segment()->itinerary()->route()->id(),
                routeName: $reg->segment()->itinerary()->route()->name(),
                modality: $reg->segment()->modality()->value,
                hash: $reg->hash(),
            ),
            $participant->registrations()->toArray(),
        );

        return new Participant(
            id: (string) $participant->id(),
            name: $participant->name(),
            surname: $participant->surname(),
            email: $participant->email(),
            registrations: $registrations,
        );
    }
}
