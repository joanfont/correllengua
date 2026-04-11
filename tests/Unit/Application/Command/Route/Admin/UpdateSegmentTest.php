<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Route\Admin;

use App\Application\Command\Route\Admin\UpdateSegment;
use App\Domain\Exception\Route\SegmentNotFoundException;
use App\Domain\Model\Coordinates;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Route\SegmentRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UpdateSegmentTest extends TestCase
{
    private readonly SegmentRepository&MockObject $segmentRepository;

    protected function setUp(): void
    {
        $this->segmentRepository = $this->createMock(SegmentRepository::class);

        self::set(SegmentRepository::class, $this->segmentRepository);
    }

    public function testUpdatesSegmentWithCorrectData(): void
    {
        $id = (string) SegmentId::generate();

        $segment = $this->createMock(Segment::class);

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($segment);

        $segment
            ->expects($this->once())
            ->method('update')
            ->with(
                2,
                $this->callback(fn (Coordinates $c): bool => 41.3851 === $c->latitude() && 2.1734 === $c->longitude()),
                $this->callback(fn (Coordinates $c): bool => 41.9794 === $c->latitude() && 2.8214 === $c->longitude()),
                50,
                10,
                Modality::BIKE,
                $this->anything(),
            );

        self::handleCommand(new UpdateSegment(
            id: $id,
            position: 2,
            startLatitude: 41.3851,
            startLongitude: 2.1734,
            endLatitude: 41.9794,
            endLongitude: 2.8214,
            capacity: 50,
            reservedCapacity: 10,
            modality: 'BIKE',
            startTime: '10:00',
        ));
    }

    public function testThrowsWhenSegmentNotFound(): void
    {
        $id = (string) SegmentId::generate();

        $this->segmentRepository
            ->expects($this->once())
            ->method('findById')
            ->willThrowException(SegmentNotFoundException::fromId(SegmentId::from($id)));

        $this->expectException(SegmentNotFoundException::class);

        self::handleCommand(new UpdateSegment(
            id: $id,
            position: 1,
            startLatitude: 41.3851,
            startLongitude: 2.1734,
            endLatitude: 41.9794,
            endLongitude: 2.8214,
            capacity: null,
            reservedCapacity: null,
            modality: 'WALK',
            startTime: '09:00',
        ));
    }
}
