<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Route\Admin;

use App\Application\Command\Route\Admin\UpdateRoute;
use App\Domain\Exception\Route\RouteNotFoundException;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\RouteRepository;
use App\Tests\TestCase;
use DateTimeInterface;
use PHPUnit\Framework\MockObject\MockObject;

class UpdateRouteTest extends TestCase
{
    private readonly RouteRepository&MockObject $routeRepository;

    protected function setUp(): void
    {
        $this->routeRepository = $this->createMock(RouteRepository::class);

        self::set(RouteRepository::class, $this->routeRepository);
    }

    public function testUpdatesRouteWithCorrectData(): void
    {
        $id = (string) RouteId::generate();
        $name = 'Correllengua 2026 Updated';
        $description = 'Updated description';
        $position = 2;
        $startsAt = '2026-04-26T09:00:00+02:00';

        $route = $this->createMock(Route::class);

        $this->routeRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($route);

        $route
            ->expects($this->once())
            ->method('update')
            ->with(
                $name,
                $description,
                $position,
                $this->callback(fn (DateTimeInterface $d): bool => $d->format(DateTimeInterface::ATOM) === $startsAt),
            );

        self::handleCommand(new UpdateRoute(
            id: $id,
            name: $name,
            description: $description,
            position: $position,
            startsAt: $startsAt,
        ));
    }

    public function testThrowsWhenRouteNotFound(): void
    {
        $id = (string) RouteId::generate();

        $this->routeRepository
            ->expects($this->once())
            ->method('findById')
            ->willThrowException(RouteNotFoundException::fromId(RouteId::from($id)));

        $this->expectException(RouteNotFoundException::class);

        self::handleCommand(new UpdateRoute(
            id: $id,
            name: 'Test',
            description: 'Test',
            position: 1,
            startsAt: '2026-04-26T09:00:00+02:00',
        ));
    }
}
