<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Participant\Admin;

use App\Domain\DTO\Admin\Participant\Participant;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Model\Participant\Participant as ParticipantEntity;
use App\Domain\Model\Registration\Registration as RegistrationEntity;
use App\Domain\Provider\Participant\Admin\ParticipantProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;

use function array_column;
use function array_map;
use function array_pop;
use function count;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineParticipantProvider extends DoctrineProvider implements ParticipantProvider
{
    public function __construct(
        private readonly AdminParticipantFactory $factory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @return PaginatedResult<Participant>
     */
    public function findAllPaginated(
        ?string $routeId,
        ?string $itineraryId,
        ?string $segmentId,
        ?int $maxOccupancy,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult {
        $baseQb = $this->buildBaseQueryBuilder($routeId, $itineraryId, $segmentId, $maxOccupancy, $cursor);

        // Count distinct participants
        $countQb = clone $baseQb;
        $countQb->select('COUNT(DISTINCT p.id)');
        $total = (int) $countQb->getQuery()->getSingleScalarResult();

        // Fetch one extra to detect next page
        $idQb = clone $baseQb;
        $idQb->select('DISTINCT p.id')
            ->setMaxResults($limit + 1);
        /** @var array<array{id: string}> $rows */
        $rows = $idQb->getQuery()->getScalarResult();

        $hasNextPage = count($rows) > $limit;
        if ($hasNextPage) {
            array_pop($rows);
        }

        $ids = array_column($rows, 'id');

        $nextCursor = null;
        if ($hasNextPage && [] !== $ids) {
            $nextCursor = Cursor::fromValue($ids[count($ids) - 1]);
        }

        if ([] === $ids) {
            return new PaginatedResult(items: [], total: $total, nextCursor: null);
        }

        // Load full entities with all their registrations for the given IDs
        /** @var array<ParticipantEntity> $entities */
        $entities = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(ParticipantEntity::class, 'p')
            ->innerJoin('p.registrations', 'reg')
            ->addSelect('reg')
            ->innerJoin('reg.segment', 's')
            ->addSelect('s')
            ->innerJoin('s.itinerary', 'i')
            ->addSelect('i')
            ->innerJoin('i.route', 'r')
            ->addSelect('r')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();

        $items = array_map(fn (ParticipantEntity $p) => $this->factory->fromEntity($p), $entities);

        return new PaginatedResult(items: $items, total: $total, nextCursor: $nextCursor);
    }

    private function buildBaseQueryBuilder(
        ?string $routeId,
        ?string $itineraryId,
        ?string $segmentId,
        ?int $maxOccupancy,
        ?Cursor $cursor,
    ): QueryBuilder {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(ParticipantEntity::class, 'p')
            ->innerJoin('p.registrations', 'reg')
            ->innerJoin('reg.segment', 's')
            ->innerJoin('s.itinerary', 'i')
            ->innerJoin('i.route', 'r')
            ->orderBy('p.id', 'ASC');

        $this->applyFilters($qb, $routeId, $itineraryId, $segmentId);
        $this->applyOccupancyFilter($qb, $maxOccupancy);

        if (null !== $cursor) {
            $qb->andWhere('p.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        return $qb;
    }

    private function applyFilters(
        QueryBuilder $qb,
        ?string $routeId,
        ?string $itineraryId,
        ?string $segmentId,
    ): void {
        if (null !== $segmentId) {
            $qb->andWhere('s.id = :segmentId')
                ->setParameter('segmentId', $segmentId);
        } elseif (null !== $itineraryId) {
            $qb->andWhere('i.id = :itineraryId')
                ->setParameter('itineraryId', $itineraryId);
        } elseif (null !== $routeId) {
            $qb->andWhere('r.id = :routeId')
                ->setParameter('routeId', $routeId);
        }
    }

    private function applyOccupancyFilter(QueryBuilder $qb, ?int $maxOccupancy): void
    {
        if (null === $maxOccupancy) {
            return;
        }

        $sub = $this->entityManager->createQueryBuilder()
            ->select('IDENTITY(regSub.segment)')
            ->from(RegistrationEntity::class, 'regSub')
            ->innerJoin('regSub.segment', 'sSub')
            ->where('sSub.capacity IS NOT NULL')
            ->groupBy('regSub.segment')
            ->having('(COUNT(regSub.id) * 100.0 / sSub.capacity) >= :maxOccupancy');

        $qb->andWhere($qb->expr()->in('s.id', $sub->getDQL()))
            ->setParameter('maxOccupancy', $maxOccupancy);
    }
}
