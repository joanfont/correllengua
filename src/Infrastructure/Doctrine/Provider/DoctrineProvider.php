<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider;

use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;

use function array_map;
use function array_pop;
use function count;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineProvider
{
    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @template TEntity
     * @template TDto
     *
     * @param QueryBuilder $qb Query already filtered and ordered, without limit
     * @param string $countExpr DQL expression for COUNT (e.g. 'COUNT(r.id)')
     * @param callable(TEntity): TDto $toDto Maps an entity to its DTO
     * @param callable(TEntity): string $toCursorValue Extracts the raw cursor value from an entity
     *
     * @return PaginatedResult<TDto>
     */
    protected function paginate(
        QueryBuilder $qb,
        string $countExpr,
        int $limit,
        callable $toDto,
        callable $toCursorValue,
    ): PaginatedResult {
        $countQb = clone $qb;
        $countQb->select($countExpr);
        $total = (int) $countQb->getQuery()->getSingleScalarResult();

        $qb->setMaxResults($limit + 1);
        /** @var array<TEntity> $entities */
        $entities = $qb->getQuery()->getResult();

        $hasNextPage = count($entities) > $limit;
        if ($hasNextPage) {
            array_pop($entities);
        }

        $items = array_map($toDto, $entities);

        $nextCursor = null;
        if ($hasNextPage && count($entities) > 0) {
            /** @var TEntity $last */
            $last = $entities[count($entities) - 1];
            $nextCursor = Cursor::fromValue($toCursorValue($last));
        }

        return new PaginatedResult(items: $items, total: $total, nextCursor: $nextCursor);
    }
}
