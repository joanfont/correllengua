<?php

namespace App\Application\Command\Route;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Domain\Model\Route\Route as RouteModel;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\RouteRepository;

readonly class ImportRoutesFromFileHandler implements CommandHandler
{
    public function __construct(
        private Filesystem $filesystem,
        private CSVReaderFactory $csvReaderFactory,
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

    /**
     * @param array{
     *     name: string
     * } $route
     * @return void
     */
    private function createRoute(array $route): void
    {
        $route = new RouteModel(
            id: RouteId::generate(),
            name: $route['name'],
        );

        $this->routeRepository->add($route);
    }
}
