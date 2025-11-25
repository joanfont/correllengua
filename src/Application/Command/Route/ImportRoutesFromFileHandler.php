<?php

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
        $csvData = $this->filesystem->read($importRoutesFromFile->path);
        $csvReader = $this->csvReaderFactory->makeFromString($csvData);
        foreach ($csvReader->readLine() as $route) {
            $this->createRoute($route);
        }
    }

    private function createRoute(array $route): void
    {
        $parsedRoute = $this->routeBuilder->fromArray($route);
        $route = new Route(
            id: RouteId::generate(),
            name: $parsedRoute->name,
            description: $parsedRoute->description,
            startsAt: $parsedRoute->startDate,
        );

        $this->routeRepository->add($route);
    }
}
