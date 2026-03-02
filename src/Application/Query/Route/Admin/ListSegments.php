<?php

declare(strict_types=1);

namespace App\Application\Query\Route\Admin;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Admin\Route\AdminSegment;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @implements Query<PaginatedResult<AdminSegment>>
 */
readonly class ListSegments implements Query
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $itineraryId,
        #[Assert\Uuid]
        public ?string $routeId,
        #[Assert\Choice(choices: ['WALK', 'BIKE', 'MIXED', 'END'])]
        public ?string $modality,
        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        public int $limit,
        #[Assert\Range(min: 0, max: 100)]
        public ?int $maxOccupancy,
        public ?Cursor $cursor,
    ) {
    }
}
