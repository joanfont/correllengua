<?php

declare(strict_types=1);

namespace App\Domain\DTO\Common;

/**
 * @template T
 */
readonly class PaginatedResult
{
    /**
     * @param array<T> $items
     */
    public function __construct(
        public array $items,
        public int $total,
        public ?Cursor $nextCursor,
    ) {
    }
}
