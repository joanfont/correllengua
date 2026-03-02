<?php

declare(strict_types=1);

namespace App\Application\Query\Route\Admin;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Admin\Route\AdminRoute;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @implements Query<PaginatedResult<AdminRoute>>
 */
readonly class ListRoutes implements Query
{
    public function __construct(
        #[Assert\Length(max: 255)]
        public ?string $name,
        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        public int $limit,
        public ?Cursor $cursor,
    ) {
    }
}
