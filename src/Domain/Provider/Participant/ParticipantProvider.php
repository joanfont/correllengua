<?php

declare(strict_types=1);

namespace App\Domain\Provider\Participant;

use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\DTO\Participant\Participant;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\RouteId;
use App\Domain\Model\Route\SegmentId;

interface ParticipantProvider
{
    public function findByEmail(string $email): Participant;

    /**
     * @return PaginatedResult<Participant>
     */
    public function findAllPaginated(
        ?RouteId $routeId,
        ?ItineraryId $itineraryId,
        ?SegmentId $segmentId,
        ?int $maxOccupancy,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult;
}
