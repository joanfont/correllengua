<?php

declare(strict_types=1);

namespace App\Application\Query\Participant\Admin;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Admin\Participant\Participant;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @implements Query<PaginatedResult<Participant>>
 */
readonly class ListParticipants implements Query
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $routeId,
        #[Assert\Uuid]
        public ?string $itineraryId,
        #[Assert\Uuid]
        public ?string $segmentId,
        #[Assert\Range(min: 0, max: 100)]
        public ?int $maxOccupancy,
        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        public int $limit,
        public ?Cursor $cursor,
    ) {
    }
}
