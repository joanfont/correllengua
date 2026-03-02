<?php

declare(strict_types=1);

namespace App\Application\Query\Route\Admin;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Admin\Route\AdminRoute;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Provider\Route\Admin\RouteProvider;

readonly class ListRoutesHandler implements QueryHandler
{
    public function __construct(private RouteProvider $routeProvider)
    {
    }

    /**
     * @return PaginatedResult<AdminRoute>
     */
    public function __invoke(ListRoutes $query): PaginatedResult
    {
        return $this->routeProvider->findAllPaginated(
            name: $query->name,
            limit: $query->limit,
            maxOccupancy: $query->maxOccupancy,
            cursor: $query->cursor,
        );
    }
}
