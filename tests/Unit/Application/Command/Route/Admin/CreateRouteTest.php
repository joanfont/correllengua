<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Route\Admin;

use App\Application\Command\Route\Admin\CreateRoute;
use App\Domain\Model\Route\Route;
use App\Domain\Repository\Route\RouteRepository;
use App\Tests\TestCase;
use DateTimeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;

class CreateRouteTest extends TestCase
{
    private readonly RouteRepository&MockObject $routeRepository;

    protected function setUp(): void
    {
        $this->routeRepository = $this->createMock(RouteRepository::class);

        self::set(RouteRepository::class, $this->routeRepository);
    }

    public function testCreatesRouteWithCorrectData(): void
    {
        $name = 'Correllengua 2026';
        $description = 'Annual language run';
        $position = 1;
        $startsAt = '2026-04-25T09:00:00+02:00';

        $this->routeRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(
                fn (Route $route): bool => Uuid::isValid((string) $route->id())
                    && $name === $route->name()
                    && $description === $route->description()
                    && $position === $route->position()
                    && $route->startsAt()->format(DateTimeInterface::ATOM) === $startsAt,
            ));

        self::handleCommand(new CreateRoute(
            name: $name,
            description: $description,
            position: $position,
            startsAt: $startsAt,
        ));
    }
}
