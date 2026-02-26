<?php

declare(strict_types=1);

namespace App\Application\Command\Route;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Application\Service\Route\RouteBuilder;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\RouteRepository;

readonly class ImportRoutesFromFileHandler implements CommandHandler
{
    public function __construct(
        private Filesystem $filesystem,
        private CSVReaderFactory $csvReaderFactory,
        private RouteBuilder $routeBuilder,
        private RouteRepository $routeRepository,
    ) {
    }

    public function __invoke(ImportRoutesFromFile $importRoutesFromFile): void
    {
        if ($importRoutesFromFile->truncate) {
            $this->routeRepository->deleteAll();
        }

        $csvData = $this->filesystem->read($importRoutesFromFile->path);
        $csvReader = $this->csvReaderFactory->makeFromString($csvData);
        foreach ($csvReader->readLine() as $route) {
            /* @var array<string, string> $route */
            $this->createRoute($route);
        }
    }

    /**
     * @param array<string, string> $route
     */
    private function createRoute(array $route): void
    {
        /**
         * @var array{
         *     name: string,
         *     description: string,
         *     position: string,
         *     start_date: string
         * } $payload
         */
        $payload = [
            'name' => $route['name'] ?? '',
            'description' => $route['description'] ?? '',
            'position' => $route['position'] ?? '',
            'start_date' => $route['start_date'] ?? '',
        ];

        $parsedRoute = $this->routeBuilder->fromArray($payload);
        $route = new Route(
            id: RouteId::generate(),
            name: $parsedRoute->name,
            description: $parsedRoute->description,
            position: $parsedRoute->position,
            startsAt: $parsedRoute->startDate,
        );

        $this->routeRepository->add($route);
    }
}
