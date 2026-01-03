<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Route;

use App\Application\Command\Route\ImportItinerariesFromFile;
use App\Application\Service\CSV\CSVReader;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Application\Service\Route\DTO\Itinerary as ItineraryDTO;
use App\Application\Service\Route\ItineraryBuilder;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\Route;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\RouteRepository;
use App\Tests\TestCase;

use function array_unique;

use ArrayIterator;
use PHPUnit\Framework\MockObject\MockObject;

class ImportItinerariesFromFileTest extends TestCase
{
    private readonly Filesystem&MockObject $filesystem;

    private readonly CSVReaderFactory&MockObject $csvReaderFactory;

    private readonly ItineraryBuilder&MockObject $itineraryBuilder;

    private readonly RouteRepository&MockObject $routeRepository;

    private readonly ItineraryRepository&MockObject $itineraryRepository;

    private readonly CSVReader&MockObject $csvReader;

    private readonly Route&MockObject $route;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->csvReaderFactory = $this->createMock(CSVReaderFactory::class);
        $this->itineraryBuilder = $this->createMock(ItineraryBuilder::class);
        $this->routeRepository = $this->createMock(RouteRepository::class);
        $this->itineraryRepository = $this->createMock(ItineraryRepository::class);
        $this->csvReader = $this->createMock(CSVReader::class);
        $this->route = $this->createMock(Route::class);

        self::set('app.route.import_segments_from_file.filesystem', $this->filesystem);
        self::set(CSVReaderFactory::class, $this->csvReaderFactory);
        self::set(ItineraryBuilder::class, $this->itineraryBuilder);
        self::set(RouteRepository::class, $this->routeRepository);
        self::set(ItineraryRepository::class, $this->itineraryRepository);
    }

    public function testImportsSingleItineraryFromFile(): void
    {
        $filePath = '/path/to/itineraries.csv';
        $csvContent = "route_name,name\nRoute 1,Itinerary A";

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
            ['route_name' => 'Route 1', 'name' => 'Itinerary A'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $itineraryDTO = new ItineraryDTO(
            'Route 1',
            'Itinerary A',
        );

        $this->itineraryBuilder
            ->expects($this->once())
            ->method('fromArray')
            ->with($csvData[0])
            ->willReturnCallback(fn (array $d): ItineraryDTO => $itineraryDTO);

        $this->routeRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('Route 1')
            ->willReturn($this->route);

        $this->itineraryRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(fn (Itinerary $itinerary): bool => 'Itinerary A' === $itinerary->name()));

        $command = new ImportItinerariesFromFile($filePath);

        self::handleCommand($command);
    }

    public function testImportsMultipleItinerariesFromFile(): void
    {
        $filePath = '/path/to/itineraries.csv';
        $csvContent = "route_name,name\nRoute 1,Itinerary A\nRoute 1,Itinerary B\nRoute 2,Itinerary C";

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
            ['route_name' => 'Route 1', 'name' => 'Itinerary A'],
            ['route_name' => 'Route 1', 'name' => 'Itinerary B'],
            ['route_name' => 'Route 2', 'name' => 'Itinerary C'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $this->itineraryBuilder
            ->expects($this->exactly(3))
            ->method('fromArray')
            ->willReturnCallback(fn (array $data): ItineraryDTO => new ItineraryDTO(
                $data['route_name'],
                $data['name'],
            ));

        $this->routeRepository
            ->expects($this->exactly(3))
            ->method('findByName')
            ->willReturnCallback(fn (string $name): Route => $this->route);

        $addedItineraries = [];
        $this->itineraryRepository
            ->expects($this->exactly(3))
            ->method('add')
            ->with($this->callback(function (Itinerary $itinerary) use (&$addedItineraries): bool {
                $addedItineraries[] = $itinerary->name();

                return true;
            }));

        $command = new ImportItinerariesFromFile($filePath);

        self::handleCommand($command);

        self::assertEquals(['Itinerary A', 'Itinerary B', 'Itinerary C'], $addedItineraries);
    }

    public function testImportsEmptyFileWithoutAddingItineraries(): void
    {
        $filePath = '/path/to/empty.csv';
        $csvContent = "route_name,name\n";

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

        $this->itineraryBuilder
            ->expects($this->never())
            ->method('fromArray');

        $this->routeRepository
            ->expects($this->never())
            ->method('findByName');

        $this->itineraryRepository
            ->expects($this->never())
            ->method('add');

        $command = new ImportItinerariesFromFile($filePath);

        self::handleCommand($command);
    }

    public function testGeneratesUniqueIdsForEachItinerary(): void
    {
        $filePath = '/path/to/itineraries.csv';
        $csvContent = "route_name,name\nRoute 1,Itinerary X\nRoute 1,Itinerary Y";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            ['route_name' => 'Route 1', 'name' => 'Itinerary X'],
            ['route_name' => 'Route 1', 'name' => 'Itinerary Y'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $this->itineraryBuilder
            ->expects($this->exactly(2))
            ->method('fromArray')
            ->willReturnCallback(fn (array $data): ItineraryDTO => new ItineraryDTO(
                $data['route_name'],
                $data['name'],
            ));

        $this->routeRepository
            ->expects($this->exactly(2))
            ->method('findByName')
            ->willReturn($this->route);

        $itineraryIds = [];
        $this->itineraryRepository
            ->expects($this->exactly(2))
            ->method('add')
            ->with($this->callback(function (Itinerary $itinerary) use (&$itineraryIds): bool {
                $id = (string) $itinerary->id();
                $itineraryIds[] = $id;

                return true;
            }));

        $command = new ImportItinerariesFromFile($filePath);

        self::handleCommand($command);

        self::assertCount(2, array_unique($itineraryIds));
    }

    public function testEachItineraryHasCorrectProperties(): void
    {
        $filePath = '/path/to/itineraries.csv';
        $csvContent = "route_name,name\nRoute Test,Itinerary Test";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            ['route_name' => 'Route Test', 'name' => 'Itinerary Test'],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $itineraryDTO = new ItineraryDTO(
            'Route Test',
            'Itinerary Test',
        );

        $this->itineraryBuilder
            ->expects($this->once())
            ->method('fromArray')
            ->with($csvData[0])
            ->willReturnCallback(fn (array $d): ItineraryDTO => $itineraryDTO);

        $this->routeRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('Route Test')
            ->willReturn($this->route);

        $this->itineraryRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Itinerary $itinerary): bool {
                self::assertInstanceOf(ItineraryId::class, $itinerary->id());
                self::assertEquals('Itinerary Test', $itinerary->name());

                return true;
            }));

        $command = new ImportItinerariesFromFile($filePath);

        self::handleCommand($command);
    }
}
