<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Route\Itinerary;
use App\Domain\Model\Route\Itinerary as ItineraryEntity;

use function array_map;

readonly class ItineraryFactory
{
    public function __construct(private SegmentFactory $segmentFactory)
    {
    }

    public function fromEntity(ItineraryEntity $itinerary): Itinerary
    {
        return new Itinerary(
            id: (string) $itinerary->id(),
            name: $itinerary->name(),
            segments: array_map(
                $this->segmentFactory->fromEntity(...),
                $itinerary->segments(),
            ),
        );
    }
}
