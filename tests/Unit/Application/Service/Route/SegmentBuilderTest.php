<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service\Route;

use App\Application\Service\Calendar\Calendar;
use App\Application\Service\Route\DTO\Segment as SegmentDTO;
use App\Application\Service\Route\SegmentBuilder;
use App\Tests\TestCase;
use DateTimeImmutable;

class SegmentBuilderTest extends TestCase
{
    public function testFromArrayReturnsSegmentDTO(): void
    {
        $input = [
            'itinerary_name' => 'Itinerary Test',
            'position' => '2',
            'start_latitude' => '41.123456',
            'start_longitude' => '2.123456',
            'end_latitude' => '41.654321',
            'end_longitude' => '2.654321',
            'modality' => 'WALK',
            'capacity' => '100',
            'start_time' => '08:30:00',
        ];

        $calendar = $this->createMock(Calendar::class);
        $calendar->expects($this->once())
            ->method('fromString')
            ->with('08:30:00', 'H:i:s')
            ->willReturn(new DateTimeImmutable('08:30:00'));

        $parser = new SegmentBuilder($calendar);

        $segment = $parser->fromArray($input);

        self::assertInstanceOf(SegmentDTO::class, $segment);
        self::assertSame($input['itinerary_name'], $segment->itineraryName);
        self::assertSame(2, $segment->position);
        self::assertEqualsWithDelta(41.123456, $segment->startLatitude, 0.000001);
        self::assertEqualsWithDelta(2.123456, $segment->startLongitude, 0.000001);
        self::assertEqualsWithDelta(41.654321, $segment->endLatitude, 0.000001);
        self::assertEqualsWithDelta(2.654321, $segment->endLongitude, 0.000001);
        self::assertSame($input['modality'], $segment->modality);
        self::assertSame(100, $segment->capacity);
    }
}
