<?php

namespace App\Tests\Unit\Application\Service\Route;

use App\Application\Service\Route\DTO\Segment as SegmentDTO;
use App\Application\Service\Route\SegmentBuilder;
use App\Tests\TestCase;

class SegmentBuilderTest extends TestCase
{
    public function testFromArrayReturnsSegmentDTO(): void
    {
        $input = [
            'itinerary_name' => 'Itinerary Test',
            'position' => 2,
            'start_latitude' => 41.123456,
            'start_longitude' => 2.123456,
            'end_latitude' => 41.654321,
            'end_longitude' => 2.654321,
            'modality' => 'WALK',
            'capacity' => 100,
        ];

        $parser = new SegmentBuilder();

        $segment = $parser->fromArray($input);

        static::assertInstanceOf(SegmentDTO::class, $segment);
        static::assertSame($input['itinerary_name'], $segment->itineraryName);
        static::assertSame($input['position'], $segment->position);
        static::assertEqualsWithDelta($input['start_latitude'], $segment->startLatitude, 0.000001);
        static::assertEqualsWithDelta($input['start_longitude'], $segment->startLongitude, 0.000001);
        static::assertEqualsWithDelta($input['end_latitude'], $segment->endLatitude, 0.000001);
        static::assertEqualsWithDelta($input['end_longitude'], $segment->endLongitude, 0.000001);
        static::assertSame($input['modality'], $segment->modality);
        static::assertSame($input['capacity'], $segment->capacity);
    }
}
