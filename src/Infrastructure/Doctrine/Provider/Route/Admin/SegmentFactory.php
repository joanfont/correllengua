<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminSegment;
use App\Domain\DTO\Coordinates;
use App\Domain\Model\Route\Segment as SegmentEntity;
use DateTimeInterface;

readonly class SegmentFactory
{
    public function fromEntity(SegmentEntity $segment, int $enrolments): AdminSegment
    {
        return new AdminSegment(
            id: (string) $segment->id(),
            position: $segment->position(),
            start: new Coordinates($segment->start()->latitude(), $segment->start()->longitude()),
            end: new Coordinates($segment->end()->latitude(), $segment->end()->longitude()),
            capacity: $segment->capacity(),
            modality: $segment->modality()->value,
            startTime: $segment->startTime()->format(DateTimeInterface::ATOM),
            itineraryId: (string) $segment->itinerary()->id(),
            itineraryName: $segment->itinerary()->name(),
            routeId: (string) $segment->itinerary()->route()->id(),
            routeName: $segment->itinerary()->route()->name(),
            enrolments: $enrolments,
        );
    }
}
