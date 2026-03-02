<?php

declare(strict_types=1);

namespace App\Application\Query\Route\Admin;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Admin\Route\AdminItinerary;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Provider\Route\ItineraryProvider;

readonly class ListItinerariesHandler implements QueryHandler
{
    public function __construct(private ItineraryProvider $itineraryProvider)
    {
    }

    /**
     * @return PaginatedResult<AdminItinerary>
     */
    public function __invoke(ListItineraries $query): PaginatedResult
    {
        return $this->itineraryProvider->findAllPaginated(
            name: $query->name,
            routeId: $query->routeId,
            limit: $query->limit,
            cursor: $query->cursor,
        );
    }
}
