<?php

namespace App\Tests\Unit\Application\Command\Route;

use App\Application\Command\Route\ImportRoutesFromFile;
use App\Application\Service\CSV\CSVReader;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Application\Service\Route\DTO\Route as RouteDTO;
use App\Application\Service\Route\RouteBuilder;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\RouteRepository;
use App\Tests\TestCase;

use function array_unique;

use ArrayIterator;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;

class ImportRoutesFromFileTest extends TestCase
{
    private readonly Filesystem&MockObject $filesystem;

    private readonly CSVReaderFactory&MockObject $csvReaderFactory;

    private readonly RouteBuilder&MockObject $routeParser;

    private readonly RouteRepository&MockObject $routeRepository;

    private readonly CSVReader&MockObject $csvReader;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->csvReaderFactory = $this->createMock(CSVReaderFactory::class);
        $this->routeParser = $this->createMock(RouteBuilder::class);
        $this->routeRepository = $this->createMock(RouteRepository::class);
        $this->csvReader = $this->createMock(CSVReader::class);

        self::set('app.route.import_routes_from_file.filesystem', $this->filesystem);
        self::set(CSVReaderFactory::class, $this->csvReaderFactory);
        self::set(RouteBuilder::class, $this->routeParser);
        self::set(RouteRepository::class, $this->routeRepository);
    }

    public function testImportsSingleRouteFromFile(): void
    {
        $filePath = '/path/to/routes.csv';
        $csvContent = "code,name,description,start_date\n1,Ruta 1,Descripció,2025-01-01";

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
            ['name' => 'Ruta 1', 'description' => 'Descripció', 'start_date' => '2025-01-01'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        /** @var RouteDTO $parsedRoute */
        $parsedRoute = new RouteDTO(
            'Ruta 1',
            'Descripció',
            new DateTimeImmutable('2025-01-01'),
        );

        $this->routeParser
            ->expects($this->once())
            ->method('fromArray')
            ->with(['name' => 'Ruta 1', 'description' => 'Descripció', 'start_date' => '2025-01-01'])
            ->willReturn($parsedRoute);

        $this->routeRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(fn (Route $route): bool => 'Ruta 1' === $route->name()
                && 'Descripció' === $route->description()));

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);
    }

    public function testImportsMultipleRoutesFromFile(): void
    {
        $filePath = '/path/to/routes.csv';
        $csvContent = "code,name,description,start_date\n1,Ruta 1,Desc 1,2025-01-01\n2,Ruta 2,Desc 2,2025-02-01\n3,Ruta 3,Desc 3,2025-03-01";

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
            ['code' => '1', 'name' => 'Ruta 1', 'description' => 'Desc 1', 'start_date' => '2025-01-01'],
            ['code' => '2', 'name' => 'Ruta 2', 'description' => 'Desc 2', 'start_date' => '2025-02-01'],
            ['code' => '3', 'name' => 'Ruta 3', 'description' => 'Desc 3', 'start_date' => '2025-03-01'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $this->routeParser
            ->expects($this->exactly(3))
            ->method('fromArray')
            /* @return RouteDTO */
            ->willReturnCallback(fn (array $data): RouteDTO => new RouteDTO(
                $data['name'],
                $data['description'],
                new DateTimeImmutable($data['start_date']),
            ));

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
        $csvContent = "code,name,description,start_date\n";

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
            ->willReturn(new ArrayIterator([]));

        $this->routeParser
            ->expects($this->never())
            ->method('fromArray');

        $this->routeRepository
            ->expects($this->never())
            ->method('add');

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);
    }

    public function testGeneratesUniqueIdsForEachRoute(): void
    {
        $filePath = '/path/to/routes.csv';
        $csvContent = "code,name,description,start_date\n1,Ruta A,Desc A,2025-01-01\n2,Ruta B,Desc B,2025-01-01";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            ['code' => '1', 'name' => 'Ruta A', 'description' => 'Desc A', 'start_date' => '2025-01-01'],
            ['code' => '2', 'name' => 'Ruta B', 'description' => 'Desc B', 'start_date' => '2025-01-01'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $this->routeParser
            ->expects($this->exactly(2))
            ->method('fromArray')
            /* @return RouteDTO */
            ->willReturnCallback(fn (array $data): RouteDTO => new RouteDTO(
                $data['name'],
                $data['description'],
                new DateTimeImmutable($data['start_date']),
            ));

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
        $csvContent = "code,name,description,start_date\n5,Ruta de Prova,Descripció de prova,2025-06-15";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            ['code' => '5', 'name' => 'Ruta de Prova', 'description' => 'Descripció de prova', 'start_date' => '2025-06-15'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        /** @var RouteDTO $parsedRoute */
        $parsedRoute = new RouteDTO(
            'Ruta de Prova',
            'Descripció de prova',
            new DateTimeImmutable('2025-06-15'),
        );

        $this->routeParser
            ->expects($this->once())
            ->method('fromArray')
            ->with(['name' => 'Ruta de Prova', 'description' => 'Descripció de prova', 'start_date' => '2025-06-15'])
            ->willReturn($parsedRoute);

        $this->routeRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Route $route): bool {
                self::assertInstanceOf(RouteId::class, $route->id());
                self::assertEquals('Ruta de Prova', $route->name());
                self::assertEquals('Descripció de prova', $route->description());
                self::assertEquals('2025-06-15', $route->startsAt()->format('Y-m-d'));

                return true;
            }));

        $command = new ImportRoutesFromFile($filePath);

        self::handleCommand($command);
    }
}
