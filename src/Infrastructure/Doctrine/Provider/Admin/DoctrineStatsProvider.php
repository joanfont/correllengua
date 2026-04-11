<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Admin;

use App\Domain\DTO\Admin\Stats\EnrolmentsPerDay;
use App\Domain\DTO\Admin\Stats\Stats;
use App\Domain\Model\Registration\Registration as RegistrationEntity;
use App\Domain\Model\Route\Segment as SegmentEntity;
use App\Domain\Provider\Admin\StatsProvider;

use function array_map;

use Doctrine\ORM\EntityManagerInterface;

final class DoctrineStatsProvider implements StatsProvider
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getStats(
        ?string $routeId,
        ?string $itineraryId,
        ?string $segmentId,
    ): Stats {
        return new Stats(
            enrolments: $this->countEnrolments($routeId, $itineraryId, $segmentId),
            totalCapacity: $this->sumCapacity($routeId, $itineraryId, $segmentId),
            enrolmentsPerDay: $this->enrolmentsPerDay($routeId, $itineraryId, $segmentId),
        );
    }

    private function countEnrolments(?string $routeId, ?string $itineraryId, ?string $segmentId): int
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(reg.id)')
            ->from(RegistrationEntity::class, 'reg')
            ->innerJoin('reg.segment', 's')
            ->innerJoin('s.itinerary', 'i')
            ->innerJoin('i.route', 'r');

        $this->applyFilters($qb, $routeId, $itineraryId, $segmentId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function sumCapacity(?string $routeId, ?string $itineraryId, ?string $segmentId): ?int
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('SUM(s.capacity)')
            ->from(SegmentEntity::class, 's')
            ->innerJoin('s.itinerary', 'i')
            ->innerJoin('i.route', 'r');

        $this->applyFilters($qb, $routeId, $itineraryId, $segmentId);

        $result = $qb->getQuery()->getSingleScalarResult();

        return null !== $result ? (int) $result : null;
    }

    /**
     * @return array<EnrolmentsPerDay>
     */
    private function enrolmentsPerDay(?string $routeId, ?string $itineraryId, ?string $segmentId): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('DATE(reg.createdAt) AS day, COUNT(reg.id) AS cnt')
            ->from(RegistrationEntity::class, 'reg')
            ->innerJoin('reg.segment', 's')
            ->innerJoin('s.itinerary', 'i')
            ->innerJoin('i.route', 'r')
            ->groupBy('day')
            ->orderBy('day', 'ASC');

        $this->applyFilters($qb, $routeId, $itineraryId, $segmentId);

        /** @var array<array{day: string, cnt: string}> $rows */
        $rows = $qb->getQuery()->getScalarResult();

        return array_map(
            fn (array $row): EnrolmentsPerDay => new EnrolmentsPerDay(
                date: $row['day'],
                count: (int) $row['cnt'],
            ),
            $rows,
        );
    }

    private function applyFilters(
        \Doctrine\ORM\QueryBuilder $qb,
        ?string $routeId,
        ?string $itineraryId,
        ?string $segmentId,
    ): void {
        if (null !== $segmentId) {
            $qb->andWhere('s.id = :segmentId')->setParameter('segmentId', $segmentId);
        }

        if (null !== $itineraryId) {
            $qb->andWhere('i.id = :itineraryId')->setParameter('itineraryId', $itineraryId);
        }

        if (null !== $routeId) {
            $qb->andWhere('r.id = :routeId')->setParameter('routeId', $routeId);
        }
    }
}
