<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route;

use App\Domain\DTO\Admin\Route\AdminItinerary;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

interface ItineraryProvider
{
    /**
     * @return PaginatedResult<AdminItinerary>
     */
    public function findAllPaginated(
        ?string $name,
        ?string $routeId,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult;
}
