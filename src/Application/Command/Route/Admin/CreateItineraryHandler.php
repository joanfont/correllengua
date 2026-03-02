<?php

declare(strict_types=1);

namespace App\Application\Command\Route\Admin;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\RouteRepository;

readonly class CreateItineraryHandler implements CommandHandler
{
    public function __construct(
        private RouteRepository $routeRepository,
        private ItineraryRepository $itineraryRepository,
    ) {
    }

    public function __invoke(CreateItinerary $command): void
    {
        $route = $this->routeRepository->findById(RouteId::from($command->routeId));

        $itinerary = new Itinerary(
            id: ItineraryId::generate(),
            route: $route,
            name: $command->name,
            position: $command->position,
        );

        $this->itineraryRepository->add($itinerary);
    }
}
