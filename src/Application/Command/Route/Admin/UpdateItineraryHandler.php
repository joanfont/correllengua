<?php

declare(strict_types=1);

namespace App\Application\Command\Route\Admin;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Repository\Route\ItineraryRepository;

readonly class UpdateItineraryHandler implements CommandHandler
{
    public function __construct(private ItineraryRepository $itineraryRepository)
    {
    }

    public function __invoke(UpdateItinerary $command): void
    {
        $itinerary = $this->itineraryRepository->findById(ItineraryId::from($command->id));

        $itinerary->update(
            name: $command->name,
            position: $command->position,
        );
    }
}
