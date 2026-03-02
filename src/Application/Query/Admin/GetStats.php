<?php

declare(strict_types=1);

namespace App\Application\Query\Admin;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Admin\Stats\Stats;

/**
 * @implements Query<Stats>
 */
readonly class GetStats implements Query
{
    public function __construct(
        public ?string $routeId,
        public ?string $itineraryId,
        public ?string $segmentId,
    ) {
    }
}
