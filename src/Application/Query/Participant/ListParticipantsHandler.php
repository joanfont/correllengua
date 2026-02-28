<?php

declare(strict_types=1);

namespace App\Application\Query\Participant;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\DTO\Participant\Participant;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\RouteId;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Provider\Participant\ParticipantProvider;

readonly class ListParticipantsHandler implements QueryHandler
{
    public function __construct(
        private ParticipantProvider $participantProvider,
    ) {
    }

    /**
     * @return PaginatedResult<Participant>
     */
    public function __invoke(ListParticipants $query): PaginatedResult
    {
        return $this->participantProvider->findAllPaginated(
            routeId: null !== $query->routeId ? RouteId::from($query->routeId) : null,
            itineraryId: null !== $query->itineraryId ? ItineraryId::from($query->itineraryId) : null,
            segmentId: null !== $query->segmentId ? SegmentId::from($query->segmentId) : null,
            limit: $query->limit,
            cursor: $query->cursor,
        );
    }
}
