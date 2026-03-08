<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\Route;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

interface RouteProvider
{
    /**
     * @return PaginatedResult<Route>
     */
    public function findAllPaginated(
        ?string $name,
        int $limit,
        ?int $maxOccupancy,
        ?Cursor $cursor,
    ): PaginatedResult;
}
