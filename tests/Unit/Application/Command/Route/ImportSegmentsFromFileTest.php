<?php

namespace App\Tests\Unit\Application\Command\Route;

use App\Application\Command\Route\ImportSegmentsFromFile;
use App\Application\Service\CSV\CSVReader;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Application\Service\Route\DTO\Segment as SegmentDTO;
use App\Application\Service\Route\SegmentBuilder;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\SegmentRepository;
use App\Tests\TestCase;

use function array_unique;

use ArrayIterator;
use PHPUnit\Framework\MockObject\MockObject;

class ImportSegmentsFromFileTest extends TestCase
{
    private readonly Filesystem&MockObject $filesystem;
    private readonly CSVReaderFactory&MockObject $csvReaderFactory;
    private readonly SegmentBuilder&MockObject $segmentParser;
    private readonly ItineraryRepository&MockObject $itineraryRepository;
    private readonly SegmentRepository&MockObject $segmentRepository;
    private readonly CSVReader&MockObject $csvReader;
    private readonly Itinerary&MockObject $itinerary;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->csvReaderFactory = $this->createMock(CSVReaderFactory::class);
        $this->segmentParser = $this->createMock(SegmentBuilder::class);
        $this->itineraryRepository = $this->createMock(ItineraryRepository::class);
        $this->segmentRepository = $this->createMock(SegmentRepository::class);
        $this->csvReader = $this->createMock(CSVReader::class);
        $this->itinerary = $this->createMock(Itinerary::class);

        self::set('app.route.import_segments_from_file.filesystem', $this->filesystem);
        self::set(CSVReaderFactory::class, $this->csvReaderFactory);
        self::set(SegmentBuilder::class, $this->segmentParser);
        self::set(ItineraryRepository::class, $this->itineraryRepository);
        self::set(SegmentRepository::class, $this->segmentRepository);
    }

    public function testImportsSingleSegmentFromFile(): void
    {
        $filePath = '/ruta/al/fitxer/segments.csv';
        $csvContent = "itinerary_name,position,start_latitude,start_longitude,end_latitude,end_longitude,modality,capacity\nItinerary 1,1,40.416775,-3.703790,40.417000,-3.703990,BIKE,8";

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
            [
                'itinerary_name' => 'Itinerary 1',
                'position' => '1',
                'start_latitude' => '40.416775',
                'start_longitude' => '-3.703790',
                'end_latitude' => '40.417000',
                'end_longitude' => '-3.703990',
                'modality' => 'BIKE',
                'capacity' => '8',
            ],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $segmentDTO = new SegmentDTO(
            itineraryName: 'Itinerary 1',
            position: 1,
            startLatitude: 40.416775,
            startLongitude: -3.703790,
            endLatitude: 40.417000,
            endLongitude: -3.703990,
            modality: 'BIKE',
            capacity: 8,
        );

        $this->segmentParser
            ->expects($this->once())
            ->method('fromArray')
            ->with($csvData[0])
            ->willReturnCallback(fn (array $d): SegmentDTO => $segmentDTO);

        $this->itineraryRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('Itinerary 1')
            ->willReturn($this->itinerary);

        $this->segmentRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Segment $segment): bool {
                return 1 === $segment->position()
                    && 40.416775 === $segment->start()->latitude()
                    && -3.703790 === $segment->start()->longitude()
                    && 40.417000 === $segment->end()->latitude()
                    && -3.703990 === $segment->end()->longitude()
                    && Modality::BIKE === $segment->modality()
                    && 8 === $segment->capacity();
            }));

        $command = new ImportSegmentsFromFile($filePath);

        self::handleCommand($command);
    }

    public function testImportsMultipleSegmentsFromFile(): void
    {
        $filePath = '/ruta/al/fitxer/segments.csv';
        $csvContent = "itinerary_name,position,start_latitude,start_longitude,end_latitude,end_longitude,modality,capacity\nItinerary 1,1,40.416775,-3.703790,40.417000,-3.703990,BIKE,8\nItinerary 1,2,40.417000,-3.703990,40.418500,-3.704500,WALK,4\nItinerary 2,1,41.385064,2.173404,41.385400,2.173800,MIXED,10";

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
            [
                'itinerary_name' => 'Itinerary 1',
                'position' => '1',
                'start_latitude' => '40.416775',
                'start_longitude' => '-3.703790',
                'end_latitude' => '40.417000',
                'end_longitude' => '-3.703990',
                'modality' => 'BIKE',
                'capacity' => '8',
            ],
            [
                'itinerary_name' => 'Itinerary 1',
                'position' => '2',
                'start_latitude' => '40.417000',
                'start_longitude' => '-3.703990',
                'end_latitude' => '40.418500',
                'end_longitude' => '-3.704500',
                'modality' => 'WALK',
                'capacity' => '4',
            ],
            [
                'itinerary_name' => 'Itinerary 2',
                'position' => '1',
                'start_latitude' => '41.385064',
                'start_longitude' => '2.173404',
                'end_latitude' => '41.385400',
                'end_longitude' => '2.173800',
                'modality' => 'MIXED',
                'capacity' => '10',
            ],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $this->segmentParser
            ->expects($this->exactly(3))
            ->method('fromArray')
            ->willReturnCallback(function (array $data): SegmentDTO {
                return new SegmentDTO(
                    itineraryName: $data['itinerary_name'],
                    position: (int) $data['position'],
                    startLatitude: (float) $data['start_latitude'],
                    startLongitude: (float) $data['start_longitude'],
                    endLatitude: (float) $data['end_latitude'],
                    endLongitude: (float) $data['end_longitude'],
                    modality: $data['modality'],
                    capacity: (int) $data['capacity'],
                );
            });

        $this->itineraryRepository
            ->expects($this->exactly(3))
            ->method('findByName')
            ->willReturnCallback(function (string $name): Itinerary {
                return $this->itinerary;
            });

        $addedSegments = [];
        $this->segmentRepository
            ->expects($this->exactly(3))
            ->method('add')
            ->with($this->callback(function (Segment $segment) use (&$addedSegments): bool {
                $addedSegments[] = [
                    'position' => $segment->position(),
                    'modality' => $segment->modality()->value,
                ];

                return true;
            }));

        $command = new ImportSegmentsFromFile($filePath);

        self::handleCommand($command);

        self::assertCount(3, $addedSegments);
        self::assertEquals(1, $addedSegments[0]['position']);
        self::assertEquals('BIKE', $addedSegments[0]['modality']);
        self::assertEquals(2, $addedSegments[1]['position']);
        self::assertEquals('WALK', $addedSegments[1]['modality']);
        self::assertEquals(1, $addedSegments[2]['position']);
        self::assertEquals('MIXED', $addedSegments[2]['modality']);
    }

    public function testImportsEmptyFileWithoutAddingSegments(): void
    {
        $filePath = '/ruta/al/fitxer/buit.csv';
        $csvContent = "itinerary_name,position,start_latitude,start_longitude,end_latitude,end_longitude,modality,capacity\n";

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

        $this->segmentParser
            ->expects($this->never())
            ->method('fromArray');

        $this->itineraryRepository
            ->expects($this->never())
            ->method('findByName');

        $this->segmentRepository
            ->expects($this->never())
            ->method('add');

        $command = new ImportSegmentsFromFile($filePath);

        self::handleCommand($command);
    }

    public function testGeneratesUniqueIdsForEachSegment(): void
    {
        $filePath = '/ruta/al/fitxer/segments.csv';
        $csvContent = "itinerary_name,position,start_latitude,start_longitude,end_latitude,end_longitude,modality,capacity\nItinerary 1,1,40.416775,-3.703790,40.417000,-3.703990,BIKE,8\nItinerary 1,2,40.417000,-3.703990,40.418500,-3.704500,WALK,4";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            [
                'itinerary_name' => 'Itinerary 1',
                'position' => '1',
                'start_latitude' => '40.416775',
                'start_longitude' => '-3.703790',
                'end_latitude' => '40.417000',
                'end_longitude' => '-3.703990',
                'modality' => 'BIKE',
                'capacity' => '8',
            ],
            [
                'itinerary_name' => 'Itinerary 1',
                'position' => '2',
                'start_latitude' => '40.417000',
                'start_longitude' => '-3.703990',
                'end_latitude' => '40.418500',
                'end_longitude' => '-3.704500',
                'modality' => 'WALK',
                'capacity' => '4',
            ],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $this->segmentParser
            ->expects($this->exactly(2))
            ->method('fromArray')
            ->willReturnCallback(function (array $data): SegmentDTO {
                return new SegmentDTO(
                    itineraryName: $data['itinerary_name'],
                    position: (int) $data['position'],
                    startLatitude: (float) $data['start_latitude'],
                    startLongitude: (float) $data['start_longitude'],
                    endLatitude: (float) $data['end_latitude'],
                    endLongitude: (float) $data['end_longitude'],
                    modality: $data['modality'],
                    capacity: (int) $data['capacity'],
                );
            });

        $this->itineraryRepository
            ->expects($this->exactly(2))
            ->method('findByName')
            ->willReturn($this->itinerary);

        $segmentIds = [];
        $this->segmentRepository
            ->expects($this->exactly(2))
            ->method('add')
            ->with($this->callback(function (Segment $segment) use (&$segmentIds): bool {
                $id = (string) $segment->id();
                $segmentIds[] = $id;

                return true;
            }));

        $command = new ImportSegmentsFromFile($filePath);

        self::handleCommand($command);

        self::assertCount(2, array_unique($segmentIds));
    }

    public function testEachSegmentHasCorrectProperties(): void
    {
        $filePath = '/ruta/al/fitxer/segment_prova.csv';
        $csvContent = "itinerary_name,position,start_latitude,start_longitude,end_latitude,end_longitude,modality,capacity\nItinerary Test,5,42.123456,1.234567,42.234567,1.345678,MIXED,6";

        $this->filesystem
            ->expects($this->once())
            ->method('read')
            ->willReturn($csvContent);

        $this->csvReaderFactory
            ->expects($this->once())
            ->method('makeFromString')
            ->willReturn($this->csvReader);

        $csvData = [
            [
                'itinerary_name' => 'Itinerary Test',
                'position' => '5',
                'start_latitude' => '42.123456',
                'start_longitude' => '1.234567',
                'end_latitude' => '42.234567',
                'end_longitude' => '1.345678',
                'modality' => 'MIXED',
                'capacity' => '6',
            ],
        ];

        $this->csvReader
            ->expects($this->once())
            ->method('readLine')
            ->willReturn(new ArrayIterator($csvData));

        $segmentDTO = new SegmentDTO(
            itineraryName: 'Itinerary Test',
            position: 5,
            startLatitude: 42.123456,
            startLongitude: 1.234567,
            endLatitude: 42.234567,
            endLongitude: 1.345678,
            modality: 'MIXED',
            capacity: 6,
        );

        $this->segmentParser
            ->expects($this->once())
            ->method('fromArray')
            ->with($csvData[0])
            ->willReturnCallback(fn (array $d): SegmentDTO => $segmentDTO);

        $this->itineraryRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('Itinerary Test')
            ->willReturn($this->itinerary);

        $this->segmentRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Segment $segment): bool {
                self::assertInstanceOf(SegmentId::class, $segment->id());
                self::assertEquals(5, $segment->position());
                self::assertEquals(42.123456, $segment->start()->latitude());
                self::assertEquals(1.234567, $segment->start()->longitude());
                self::assertEquals(42.234567, $segment->end()->latitude());
                self::assertEquals(1.345678, $segment->end()->longitude());
                self::assertEquals(Modality::MIXED, $segment->modality());
                self::assertEquals(6, $segment->capacity());

                return true;
            }));

        $command = new ImportSegmentsFromFile($filePath);

        self::handleCommand($command);
    }
}
