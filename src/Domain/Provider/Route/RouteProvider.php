<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route;

use App\Domain\DTO\Admin\Route\AdminRoute;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\DTO\Route\Route;

interface RouteProvider
{
    /**
     * @return array<Route>
     */
    public function findAll(): array;

    /**
     * @return PaginatedResult<AdminRoute>
     */
    public function findAllPaginated(
        ?string $name,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult;
}
