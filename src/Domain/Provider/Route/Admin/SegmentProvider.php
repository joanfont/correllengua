<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\Segment;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

interface SegmentProvider
{
    /**
     * @return PaginatedResult<Segment>
     */
    public function findAllPaginated(
        ?string $itineraryId,
        ?string $routeId,
        ?string $modality,
        int $limit,
        ?int $maxOccupancy,
        ?Cursor $cursor,
    ): PaginatedResult;
}
