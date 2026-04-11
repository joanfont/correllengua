<?php

declare(strict_types=1);

namespace App\Application\Command\Route;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Application\Service\File\Filesystem;
use App\Application\Service\Route\SegmentBuilder;
use App\Domain\Model\Coordinates;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\SegmentRepository;

readonly class ImportSegmentsFromFileHandler implements CommandHandler
{
    public function __construct(
        private Filesystem $filesystem,
        private CSVReaderFactory $csvReaderFactory,
        private SegmentBuilder $segmentBuilder,
        private ItineraryRepository $itineraryRepository,
        private SegmentRepository $segmentRepository,
    ) {
    }

    public function __invoke(ImportSegmentsFromFile $importSegmentsFromFile): void
    {
        if ($importSegmentsFromFile->truncate) {
            $this->segmentRepository->deleteAll();
        }

        $csvData = $this->filesystem->read($importSegmentsFromFile->path);
        $csvReader = $this->csvReaderFactory->makeFromString($csvData);
        foreach ($csvReader->readLine() as $segment) {
            /* @var array<string, string> $segment */
            $this->createSegment($segment);
        }
    }

    /**
     * @param array<string, string> $segment
     */
    private function createSegment(array $segment): void
    {
        /**
         * @var array{
         *     itinerary_name: string,
         *     position: string,
         *     start_latitude: string,
         *     start_longitude: string,
         *     end_latitude: string,
         *     end_longitude: string,
         *     capacity: string|null,
         *     modality: string,
         *     start_time: string,
         *  } $payload
         */
        $payload = [
            'itinerary_name' => $segment['itinerary_name'] ?? '',
            'position' => $segment['position'] ?? '',
            'start_latitude' => $segment['start_latitude'] ?? '',
            'start_longitude' => $segment['start_longitude'] ?? '',
            'end_latitude' => $segment['end_latitude'] ?? '',
            'end_longitude' => $segment['end_longitude'] ?? '',
            'capacity' => $segment['capacity'] ?? '',
            'modality' => $segment['modality'] ?? '',
            'start_time' => $segment['start_time'] ?? '',
        ];

        $parsedSegment = $this->segmentBuilder->fromArray($payload);
        $itinerary = $this->itineraryRepository->findByName($parsedSegment->itineraryName);

        $segment = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerary,
            position: $parsedSegment->position,
            start: new Coordinates($parsedSegment->startLatitude, $parsedSegment->startLongitude),
            end: new Coordinates($parsedSegment->endLatitude, $parsedSegment->endLongitude),
            capacity: $parsedSegment->capacity,
            reservedCapacity: 0,
            modality: Modality::from($parsedSegment->modality),
            startTime: $parsedSegment->startTime,
        );

        $this->segmentRepository->add($segment);
    }
}
