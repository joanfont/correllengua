<?php

declare(strict_types=1);

namespace App\Application\Service\Route;

use App\Application\Service\Route\DTO\Segment;

class SegmentBuilder
{
    /**
     * @param array{
     *     itinerary_name: string,
     *     position: int,
     *     start_latitude: float,
     *     start_longitude: float,
     *     end_latitude: float,
     *     end_longitude: float,
     *     modality: string,
     *     capacity: int
     * } $segment
     */
    public function fromArray(array $segment): Segment
    {
        return new Segment(
            itineraryName: $segment['itinerary_name'],
            position: $segment['position'],
            startLatitude: $segment['start_latitude'],
            startLongitude: $segment['start_longitude'],
            endLatitude: $segment['end_latitude'],
            endLongitude: $segment['end_longitude'],
            modality: $segment['modality'],
            capacity: $segment['capacity'],
        );
    }
}
