<?php

declare(strict_types=1);

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
    ) {
    }

    public function __invoke(ImportItinerariesFromFile $importItineraries): void
    {
        $csvData = $this->filesystem->read($importItineraries->path);
        $csvReader = $this->csvReaderFactory->makeFromString($csvData);
        foreach ($csvReader->readLine() as $route) {
            /* @var array<string, string> $route */
            $this->createItinerary($route);
        }
    }

    /**
     * @param array<string, string> $route
     */
    private function createItinerary(array $route): void
    {
        /** @var array{route_name: string, name: string} $payload */
        $payload = [
            'route_name' => $route['route_name'] ?? '',
            'name' => $route['name'] ?? '',
        ];

        $parsedItinerary = $this->itineraryBuilder->fromArray($payload);
        $route = $this->routeRepository->findByName($parsedItinerary->routeName);

        $itinerary = new Itinerary(
            id: ItineraryId::generate(),
            route: $route,
            name: $parsedItinerary->name,
        );

        $this->itineraryRepository->add($itinerary);
    }
}
