<?php

declare(strict_types=1);

namespace App\Application\Service\Route;

use App\Application\Service\Calendar\Calendar;
use App\Application\Service\Route\DTO\Segment;

readonly class SegmentBuilder
{
    public function __construct(private Calendar $calendar)
    {
    }

    /**
     * @param array{
     *     itinerary_name: string,
     *     position: string,
     *     start_latitude: string,
     *     start_longitude: string,
     *     end_latitude: string,
     *     end_longitude: string,
     *     modality: string,
     *     capacity: string|null,
     *     start_time: string,
     * } $segment
     */
    public function fromArray(array $segment): Segment
    {
        return new Segment(
            itineraryName: $segment['itinerary_name'],
            position: (int) $segment['position'],
            startLatitude: (float) $segment['start_latitude'],
            startLongitude: (float) $segment['start_longitude'],
            endLatitude: (float) $segment['end_latitude'],
            endLongitude: (float) $segment['end_longitude'],
            modality: $segment['modality'],
            capacity: null === $segment['capacity'] || '' === $segment['capacity'] ? null : (int) $segment['capacity'],
            startTime: $this->calendar->fromString($segment['start_time'], 'H:i:s'),
        );
    }
}
