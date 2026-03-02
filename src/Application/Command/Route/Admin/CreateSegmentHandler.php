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

    public function __invoke(CreateSegment $command): void
    {
        $itinerary = $this->itineraryRepository->findById(ItineraryId::from($command->itineraryId));

        $segment = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerary,
            position: $command->position,
            start: new Coordinates($command->startLatitude, $command->startLongitude),
            end: new Coordinates($command->endLatitude, $command->endLongitude),
            capacity: $command->capacity,
            modality: Modality::from($command->modality),
            startTime: new DateTimeImmutable($command->startTime),
        );

        $this->segmentRepository->add($segment);
    }
}
