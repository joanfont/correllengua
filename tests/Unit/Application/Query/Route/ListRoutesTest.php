<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query\Route;

use App\Application\Query\Route\ListRoutes;
use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Route\Itinerary;
use App\Domain\DTO\Route\Route;
use App\Domain\DTO\Route\Segment;
use App\Domain\Provider\Route\RouteProvider;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ListRoutesTest extends TestCase
{
    private readonly RouteProvider&MockObject $routeProvider;

    protected function setUp(): void
    {
        $this->routeProvider = $this->createMock(RouteProvider::class);

        self::set(RouteProvider::class, $this->routeProvider);
    }

    public function testReturnsListOfRoutes(): void
    {
        $segment1 = new Segment(
            id: 's1',
            start: new Coordinates(latitude: 41.3851, longitude: 2.1734),
            end: new Coordinates(latitude: 41.3879, longitude: 2.1699),
            capacity: 50,
            modality: 'walk',
        );

        $segment2 = new Segment(
            id: 's2',
            start: new Coordinates(latitude: 41.3879, longitude: 2.1699),
            end: new Coordinates(latitude: 41.3900, longitude: 2.1650),
            capacity: 100,
            modality: 'bike',
        );

        $itinerary1 = new Itinerary(
            id: 'i1',
            name: 'Barcelona Center',
            segments: [$segment1],
        );

        $itinerary2 = new Itinerary(
            id: 'i2',
            name: 'Barcelona Coast',
            segments: [$segment2],
        );

        $routes = [
            new Route(
                id: 'r1',
                name: 'Route 1',
                itineraries: [$itinerary1],
            ),
            new Route(
                id: 'r2',
                name: 'Route 2',
                itineraries: [$itinerary2],
            ),
        ];

        $this->routeProvider
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($routes);

        $query = new ListRoutes();

        $result = self::handleQuery($query);

        self::assertCount(2, $result);
        self::assertSame('Route 1', $result[0]->name);
        self::assertSame('Route 2', $result[1]->name);
        self::assertCount(1, $result[0]->itineraries);
        self::assertCount(1, $result[1]->itineraries);
    }

    public function testReturnsEmptyArrayWhenNoRoutes(): void
    {
        $this->routeProvider
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $query = new ListRoutes();

        $result = self::handleQuery($query);

        self::assertEmpty($result);
    }
}
