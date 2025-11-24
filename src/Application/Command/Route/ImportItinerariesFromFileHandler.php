<?php

namespace App\Application\Command\Route;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Application\Service\Route\ItineraryBuilder;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\RouteRepository;

readonly class ImportItinerariesFromFileHandler implements CommandHandler
{
    public function __construct(
        private Filesystem $filesystem,
        private CSVReaderFactory $csvReaderFactory,
        private ItineraryBuilder $itineraryBuilder,
        private RouteRepository $routeRepository,
        private ItineraryRepository $itineraryRepository,
    ) {}

    public function __invoke(ImportItinerariesFromFile $importItineraries): void
    {
        $csvData = $this->filesystem->read($importItineraries->path);
        $csvReader = $this->csvReaderFactory->makeFromString($csvData);
        foreach ($csvReader->readLine() as $route) {
            $this->createItinerary($route);
        }
    }

    private function createItinerary(array $route): void
    {
        $parsedItinerary = $this->itineraryBuilder->fromArray($route);
        $route = $this->routeRepository->findByName($parsedItinerary->routeName);

        $itinerary = new Itinerary(
            id: ItineraryId::generate(),
            route: $route,
            name: $parsedItinerary->name,
        );

        $this->itineraryRepository->add($itinerary);
    }
}
