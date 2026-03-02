<?php

declare(strict_types=1);

namespace App\Application\Command\Route\Admin;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\RouteRepository;
use DateTimeImmutable;

readonly class CreateRouteHandler implements CommandHandler
{
    public function __construct(private RouteRepository $routeRepository)
    {
    }

    public function __invoke(CreateRoute $command): void
    {
        $route = new Route(
            id: RouteId::generate(),
            name: $command->name,
            description: $command->description,
            position: $command->position,
            startsAt: new DateTimeImmutable($command->startsAt),
        );

        $this->routeRepository->add($route);
    }
}
