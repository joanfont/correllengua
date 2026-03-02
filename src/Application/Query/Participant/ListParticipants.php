<?php

declare(strict_types=1);

namespace App\Application\Query\Participant;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\DTO\Participant\Participant;

/**
 * @implements Query<PaginatedResult<Participant>>
 */
readonly class ListParticipants implements Query
{
    public function __construct(
        public ?string $routeId,
        public ?string $itineraryId,
        public ?string $segmentId,
        public ?int $maxOccupancy,
        public int $limit,
        public ?Cursor $cursor,
    ) {
    }
}
