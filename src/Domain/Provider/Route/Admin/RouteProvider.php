<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminRoute;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

interface RouteProvider
{
    /**
     * @return PaginatedResult<AdminRoute>
     */
    public function findAllPaginated(
        ?string $name,
        int $limit,
        ?int $maxOccupancy,
        ?Cursor $cursor,
    ): PaginatedResult;
}
