<?php

namespace App\Tests\Application\Command\Route;

use App\Application\Command\Route\ImportRoutesFromFile;
use App\Application\Service\CSV\CSVReader;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\RouteRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ImportRoutesFromFileTest extends TestCase
{
    private readonly Filesystem&MockObject $filesystem;
    private readonly CSVReaderFactory&MockObject $csvReaderFactory;
    private readonly RouteRepository&MockObject $routeRepository;
    private readonly CSVReader&MockObject $csvReader;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->csvReaderFactory = $this->createMock(CSVReaderFactory::class);
        $this->routeRepository = $this->createMock(RouteRepository::class);
        $this->csvReader = $this->createMock(CSVReader::class);

        self::set('app.route.import_routes_from_file.filesystem', $this->filesystem);
        self::set(CSVReaderFactory::class, $this->csvReaderFactory);
        self::set(RouteRepository::class, $this->routeRepository);
    }

    public function testImportsSingleRouteFromFile(): void
    {
        $filePath = '/path/to/routes.csv';
        $csvContent = "name\nRuta 1";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->with($filePath)
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->with($csvContent)
            ->willReturn($this->csvReader);

        $csvData = [
            ['name' => 'Ruta 1'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new \ArrayIterator($csvData));

        $this->routeRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Route $route): bool {
                return 'Ruta 1' === $route->name();
            }));

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);
    }

    public function testImportsMultipleRoutesFromFile(): void
    {
        $filePath = '/path/to/routes.csv';
        $csvContent = "name\nRuta 1\nRuta 2\nRuta 3";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->with($filePath)
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->with($csvContent)
            ->willReturn($this->csvReader);

        $csvData = [
            ['name' => 'Ruta 1'],
            ['name' => 'Ruta 2'],
            ['name' => 'Ruta 3'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new \ArrayIterator($csvData));

        $addedRoutes = [];
        $this->routeRepository
            ->expects($this->exactly(3))
            ->method('add')
            ->with($this->callback(function (Route $route) use (&$addedRoutes): bool {
                $addedRoutes[] = $route->name();

                return true;
            }));

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);

        self::assertEquals(['Ruta 1', 'Ruta 2', 'Ruta 3'], $addedRoutes);
    }

    public function testImportsEmptyFileWithoutAddingRoutes(): void
    {
        $filePath = '/path/to/empty.csv';
        $csvContent = "name\n";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->with($filePath)
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->with($csvContent)
            ->willReturn($this->csvReader);

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new \ArrayIterator([]));

        $this->routeRepository
            ->expects($this->never())
            ->method('add');

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);
    }

    public function testGeneratesUniqueIdsForEachRoute(): void
    {
        $filePath = '/path/to/routes.csv';
        $csvContent = "name\nRuta A\nRuta B";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            ['name' => 'Ruta A'],
            ['name' => 'Ruta B'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new \ArrayIterator($csvData));

        $routeIds = [];
        $this->routeRepository
            ->expects($this->exactly(2))
            ->method('add')
            ->with($this->callback(function (Route $route) use (&$routeIds): bool {
                $id = (string) $route->id();
                $routeIds[] = $id;

                return true;
            }));

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);

        self::assertCount(2, array_unique($routeIds));
    }

    public function testEachRouteHasCorrectProperties(): void
    {
        $filePath = '/path/to/routes.csv';
        $csvContent = "name\nRuta de Prueba";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            ['name' => 'Ruta de Prueba'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new \ArrayIterator($csvData));

        $this->routeRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Route $route): bool {
                self::assertInstanceOf(RouteId::class, $route->id());
                self::assertEquals('Ruta de Prueba', $route->name());

                return true;
            }));

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);
    }
}
