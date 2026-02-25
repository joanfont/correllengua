<?php

declare(strict_types=1);

namespace App\Application\Service\Route;

use App\Application\Service\Route\DTO\Itinerary;

readonly class ItineraryBuilder
{
    /**
     * @param array{
     *     route_name: string,
     *     name: string,
     * } $itinerary
     */
    public function fromArray(array $itinerary): Itinerary
    {
        return new Itinerary(
            routeName: $itinerary['route_name'],
            name: $itinerary['name'],
        );
    }
}
