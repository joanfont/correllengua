<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route;

use App\Domain\DTO\Admin\Route\AdminSegment;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

interface SegmentProvider
{
    /**
     * @return PaginatedResult<AdminSegment>
     */
    public function findAllPaginated(
        ?string $itineraryId,
        ?string $routeId,
        ?string $modality,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult;
}
