<?php

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
        /** @var array{itinerary_name: string, position: int, start_latitude: float, start_longitude: float, end_latitude: float, end_longitude: float, modality: string, capacity: int} $payload */
        $payload = [
            'itinerary_name' => $segment['itinerary_name'] ?? '',
            'position' => (int) ($segment['position'] ?? 0),
            'start_latitude' => (float) ($segment['start_latitude'] ?? 0.0),
            'start_longitude' => (float) ($segment['start_longitude'] ?? 0.0),
            'end_latitude' => (float) ($segment['end_latitude'] ?? 0.0),
            'end_longitude' => (float) ($segment['end_longitude'] ?? 0.0),
            'modality' => $segment['modality'] ?? '',
            'capacity' => (int) ($segment['capacity'] ?? 0),
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
            modality: Modality::from($parsedSegment->modality),
        );

        $this->segmentRepository->add($segment);
    }
}
