<?php

declare(strict_types=1);

namespace App\Domain\Provider\Participant\Admin;

use App\Domain\DTO\Admin\Participant\Participant;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

interface ParticipantProvider
{
    /**
     * @return PaginatedResult<Participant>
     */
    public function findAllPaginated(
        ?string $routeId,
        ?string $itineraryId,
        ?string $segmentId,
        ?int $maxOccupancy,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult;
}
