<?php

declare(strict_types=1);

namespace App\Application\Query\Admin;

use App\Application\Commons\Query\Query;

readonly class GetStats implements Query
{
    public function __construct(
        public ?string $routeId,
        public ?string $itineraryId,
        public ?string $segmentId,
    ) {
    }
}
