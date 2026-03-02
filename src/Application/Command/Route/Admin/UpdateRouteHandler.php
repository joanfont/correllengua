<?php

declare(strict_types=1);

namespace App\Application\Command\Route\Admin;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Model\Route\RouteId;
use App\Domain\Repository\Route\RouteRepository;
use DateTimeImmutable;

readonly class UpdateRouteHandler implements CommandHandler
{
    public function __construct(private RouteRepository $routeRepository)
    {
    }

    public function __invoke(UpdateRoute $command): void
    {
        $route = $this->routeRepository->findById(RouteId::from($command->id));

        $route->update(
            name: $command->name,
            description: $command->description,
            position: $command->position,
            startsAt: new DateTimeImmutable($command->startsAt),
        );
    }
}
