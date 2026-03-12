<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Route\Segment;
use App\Domain\Model\Route\Segment as SegmentEntity;

readonly class SegmentFactory
{
    public function fromEntity(SegmentEntity $segment): Segment
    {
        return new Segment(
            id: (string) $segment->id(),
            start: new Coordinates($segment->start()->latitude(), $segment->start()->longitude()),
            end: new Coordinates($segment->end()->latitude(), $segment->end()->longitude()),
            capacity: $segment->capacity(),
            modality: $segment->modality()->value,
            position: $segment->position(),
            itineraryName: $segment->itinerary()->name(),
            routeDate: $segment->itinerary()->route()->startsAt(),
            startTime: $segment->startTime(),
        );
    }
}
