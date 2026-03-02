<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Route\Admin;

use App\Application\Command\Route\Admin\CreateItinerary;
use App\Domain\Exception\Route\RouteNotFoundException;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Domain\Repository\Route\RouteRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;

class CreateItineraryTest extends TestCase
{
    private readonly RouteRepository&MockObject $routeRepository;

    private readonly ItineraryRepository&MockObject $itineraryRepository;

    protected function setUp(): void
    {
        $this->routeRepository = $this->createMock(RouteRepository::class);
        $this->itineraryRepository = $this->createMock(ItineraryRepository::class);

        self::set(RouteRepository::class, $this->routeRepository);
        self::set(ItineraryRepository::class, $this->itineraryRepository);
    }

    public function testCreatesItineraryUnderExistingRoute(): void
    {
        $route = $this->createMock(Route::class);
        $routeId = (string) RouteId::generate();
        $name = 'Costa Brava';
        $position = 1;

        $this->routeRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($route);

        $this->itineraryRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(
                fn (Itinerary $itinerary): bool => Uuid::isValid((string) $itinerary->id())
                    && $name === $itinerary->name()
                    && $position === $itinerary->position()
                    && $itinerary->route() === $route,
            ));

        self::handleCommand(new CreateItinerary(
            routeId: $routeId,
            name: $name,
            position: $position,
        ));
    }

    public function testThrowsWhenRouteNotFound(): void
    {
        $routeId = (string) RouteId::generate();

        $this->routeRepository
            ->expects($this->once())
            ->method('findById')
            ->willThrowException(RouteNotFoundException::fromId(RouteId::from($routeId)));

        $this->itineraryRepository
            ->expects($this->never())
            ->method('add');

        $this->expectException(RouteNotFoundException::class);

        self::handleCommand(new CreateItinerary(
            routeId: $routeId,
            name: 'Costa Brava',
            position: 1,
        ));
    }
}
