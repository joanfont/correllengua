<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\Route;
use App\Domain\Model\Route\Route as RouteEntity;
use DateTimeInterface;

readonly class RouteFactory
{
    public function fromEntity(RouteEntity $route, int $enrolments): Route
    {
        return new Route(
            id: (string) $route->id(),
            name: $route->name(),
            description: $route->description(),
            position: $route->position(),
            startsAt: $route->startsAt()->format(DateTimeInterface::ATOM),
            enrolments: $enrolments,
        );
    }
}
