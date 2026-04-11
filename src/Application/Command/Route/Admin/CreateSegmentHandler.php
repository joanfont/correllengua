<?php

declare(strict_types=1);

namespace App\Application\Command\Route\Admin;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Model\Coordinates;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\SegmentRepository;
use DateTimeImmutable;

readonly class CreateSegmentHandler implements CommandHandler
{
    public function __construct(
        private ItineraryRepository $itineraryRepository,
        private SegmentRepository $segmentRepository,
    ) {
    }

    public function __invoke(CreateSegment $createSegment): void
    {
        $itinerary = $this->itineraryRepository->findById(ItineraryId::from($createSegment->itineraryId));

        $segment = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerary,
            position: $createSegment->position,
            start: new Coordinates($createSegment->startLatitude, $createSegment->startLongitude),
            end: new Coordinates($createSegment->endLatitude, $createSegment->endLongitude),
            capacity: $createSegment->capacity,
            reservedCapacity: $createSegment->reservedCapacity,
            modality: Modality::from($createSegment->modality),
            startTime: DateTimeImmutable::createFromFormat('H:i', $createSegment->startTime) ?: new DateTimeImmutable(),
        );

        $this->segmentRepository->add($segment);
    }
}
