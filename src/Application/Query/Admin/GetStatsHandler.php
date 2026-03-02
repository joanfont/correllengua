<?php

declare(strict_types=1);

namespace App\Application\Query\Admin;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Admin\Stats\Stats;
use App\Domain\Provider\Admin\StatsProvider;

readonly class GetStatsHandler implements QueryHandler
{
    public function __construct(private StatsProvider $statsProvider)
    {
    }

    public function __invoke(GetStats $query): Stats
    {
        return $this->statsProvider->getStats(
            routeId: $query->routeId,
            itineraryId: $query->itineraryId,
            segmentId: $query->segmentId,
        );
    }
}
