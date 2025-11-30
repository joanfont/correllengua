<?php

namespace App\Application\Query\Route;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Route\Route;
use App\Domain\Provider\Route\RouteProvider;

readonly class ListRoutesHandler implements QueryHandler
{
    public function __construct(private RouteProvider $routeProvider)
    {
    }

    /**
     * @return array<Route>
     */
    public function __invoke(ListRoutes $listRoutes): array
    {
        return $this->routeProvider->findAll();
    }
}
