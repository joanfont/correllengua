<?php

declare(strict_types=1);

namespace App\Application\Query\Participant\Admin;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Admin\Participant\Participant;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Provider\Participant\Admin\ParticipantProvider;

readonly class ListParticipantsHandler implements QueryHandler
{
    public function __construct(private ParticipantProvider $participantProvider)
    {
    }

    /**
     * @return PaginatedResult<Participant>
     */
    public function __invoke(ListParticipants $query): PaginatedResult
    {
        return $this->participantProvider->findAllPaginated(
            routeId: $query->routeId,
            itineraryId: $query->itineraryId,
            segmentId: $query->segmentId,
            maxOccupancy: $query->maxOccupancy,
            limit: $query->limit,
            cursor: $query->cursor,
        );
    }
}
