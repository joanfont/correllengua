<?php

namespace App\Application\Query\Route;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\Provider\RouteProvider;

readonly class ListRoutesHandler implements QueryHandler
{
    public function __construct(private RouteProvider $routeProvider)
    {
    }

    public function __invoke(ListRoutes $listRoutes): array
    {
        return $this->routeProvider->findAll();
    }
}
