<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Route\Admin;

use App\Application\Command\Route\Admin\UpdateItinerary;
use App\Domain\Exception\Route\ItineraryNotFoundException;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UpdateItineraryTest extends TestCase
{
    private readonly ItineraryRepository&MockObject $itineraryRepository;

    protected function setUp(): void
    {
        $this->itineraryRepository = $this->createMock(ItineraryRepository::class);

        self::set(ItineraryRepository::class, $this->itineraryRepository);
    }

    public function testUpdatesItineraryWithCorrectData(): void
    {
        $id = (string) ItineraryId::generate();
        $name = 'Costa Brava Updated';
        $position = 2;

        $itinerary = $this->createMock(Itinerary::class);

        $this->itineraryRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($itinerary);

        $itinerary
            ->expects($this->once())
            ->method('update')
            ->with($name, $position);

        self::handleCommand(new UpdateItinerary(
            id: $id,
            name: $name,
            position: $position,
        ));
    }

    public function testThrowsWhenItineraryNotFound(): void
    {
        $id = (string) ItineraryId::generate();

        $this->itineraryRepository
            ->expects($this->once())
            ->method('findById')
            ->willThrowException(ItineraryNotFoundException::fromId(ItineraryId::from($id)));

        $this->expectException(ItineraryNotFoundException::class);

        self::handleCommand(new UpdateItinerary(
            id: $id,
            name: 'Test',
            position: 1,
        ));
    }
}
