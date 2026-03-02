<?php

declare(strict_types=1);

namespace App\Domain\Provider\Admin;

use App\Domain\DTO\Admin\Stats\Stats;

interface StatsProvider
{
    public function getStats(
        ?string $routeId,
        ?string $itineraryId,
        ?string $segmentId,
    ): Stats;
}
