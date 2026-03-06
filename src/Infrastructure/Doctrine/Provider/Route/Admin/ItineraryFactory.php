<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminItinerary;
use App\Domain\Model\Route\Itinerary as ItineraryEntity;

readonly class ItineraryFactory
{
    public function fromEntity(ItineraryEntity $itinerary, int $enrolments): AdminItinerary
    {
        return new AdminItinerary(
            id: (string) $itinerary->id(),
            name: $itinerary->name(),
            position: $itinerary->position(),
            routeId: (string) $itinerary->route()->id(),
            routeName: $itinerary->route()->name(),
            enrolments: $enrolments,
        );
    }
}
