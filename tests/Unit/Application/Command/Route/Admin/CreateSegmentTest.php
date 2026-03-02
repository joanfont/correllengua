<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Route\Admin;

use App\Application\Command\Route\Admin\CreateSegment;
use App\Domain\Exception\Route\ItineraryNotFoundException;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\SegmentRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;

class CreateSegmentTest extends TestCase
{
    private readonly ItineraryRepository&MockObject $itineraryRepository;

    private readonly SegmentRepository&MockObject $segmentRepository;

    protected function setUp(): void
    {
        $this->itineraryRepository = $this->createMock(ItineraryRepository::class);
        $this->segmentRepository = $this->createMock(SegmentRepository::class);

        self::set(ItineraryRepository::class, $this->itineraryRepository);
        self::set(SegmentRepository::class, $this->segmentRepository);
    }

    public function testCreatesSegmentUnderExistingItinerary(): void
    {
        $itinerary = $this->createMock(Itinerary::class);
        $itineraryId = (string) ItineraryId::generate();

        $this->itineraryRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($itinerary);

        $this->segmentRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(
                fn (Segment $segment): bool => Uuid::isValid((string) $segment->id())
                    && $segment->itinerary() === $itinerary
                    && 1 === $segment->position()
                    && 41.3851 === $segment->start()->latitude()
                    && 2.1734 === $segment->start()->longitude()
                    && 41.9794 === $segment->end()->latitude()
                    && 2.8214 === $segment->end()->longitude()
                    && 100 === $segment->capacity()
                    && Modality::WALK === $segment->modality(),
            ));

        self::handleCommand(new CreateSegment(
            itineraryId: $itineraryId,
            position: 1,
            startLatitude: 41.3851,
            startLongitude: 2.1734,
            endLatitude: 41.9794,
            endLongitude: 2.8214,
            capacity: 100,
            modality: 'WALK',
            startTime: '2026-04-25T09:00:00+02:00',
        ));
    }

    public function testThrowsWhenItineraryNotFound(): void
    {
        $itineraryId = (string) ItineraryId::generate();

        $this->itineraryRepository
            ->expects($this->once())
            ->method('findById')
            ->willThrowException(ItineraryNotFoundException::fromId(ItineraryId::from($itineraryId)));

        $this->segmentRepository
            ->expects($this->never())
            ->method('add');

        $this->expectException(ItineraryNotFoundException::class);

        self::handleCommand(new CreateSegment(
            itineraryId: $itineraryId,
            position: 1,
            startLatitude: 41.3851,
            startLongitude: 2.1734,
            endLatitude: 41.9794,
            endLongitude: 2.8214,
            capacity: null,
            modality: 'BIKE',
            startTime: '2026-04-25T09:00:00+02:00',
        ));
    }
}
