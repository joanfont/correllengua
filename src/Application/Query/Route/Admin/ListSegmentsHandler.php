<?php

declare(strict_types=1);

namespace App\Application\Query\Route\Admin;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Admin\Route\AdminSegment;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Provider\Route\Admin\SegmentProvider;

readonly class ListSegmentsHandler implements QueryHandler
{
    public function __construct(private SegmentProvider $segmentProvider)
    {
    }

    /**
     * @return PaginatedResult<AdminSegment>
     */
    public function __invoke(ListSegments $query): PaginatedResult
    {
        return $this->segmentProvider->findAllPaginated(
            itineraryId: $query->itineraryId,
            routeId: $query->routeId,
            modality: $query->modality,
            limit: $query->limit,
            maxOccupancy: $query->maxOccupancy,
            cursor: $query->cursor,
        );
    }
}
