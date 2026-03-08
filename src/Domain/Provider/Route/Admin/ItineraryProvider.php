<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\Itinerary;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

interface ItineraryProvider
{
    /**
     * @return PaginatedResult<Itinerary>
     */
    public function findAllPaginated(
        ?string $name,
        ?string $routeId,
        int $limit,
        ?int $maxOccupancy,
        ?Cursor $cursor,
    ): PaginatedResult;
}
