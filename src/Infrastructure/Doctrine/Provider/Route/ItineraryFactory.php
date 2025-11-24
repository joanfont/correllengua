<?php

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Route\Itinerary;
use App\Domain\DTO\Route\Segment;
use App\Domain\Model\Route\Itinerary as ItineraryEntity;

readonly class ItineraryFactory
{
    public function __construct(private readonly SegmentFactory $segmentFactory) {}

    public function fromEntity(ItineraryEntity $itinerary): Itinerary
    {
        return new Itinerary(
            id: (string) $itinerary->id(),
            name: $itinerary->name(),
            segments: array_map(
                fn (Segment $segment) => $this->segmentFactory->fromEntity($segment),
                $itinerary->segments(),
            ),
        );
    }
}
