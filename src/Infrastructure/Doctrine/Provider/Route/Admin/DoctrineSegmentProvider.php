<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminSegment;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Model\Registration\Registration as RegistrationEntity;
use App\Domain\Model\Route\Segment as SegmentEntity;
use App\Domain\Provider\Route\Admin\SegmentProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSegmentProvider extends DoctrineProvider implements SegmentProvider
{
    public function __construct(
        private readonly SegmentFactory $adminSegmentFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @return PaginatedResult<AdminSegment>
     */
    public function findAllPaginated(
        ?string $itineraryId,
        ?string $routeId,
        ?string $modality,
        int $limit,
        ?int $maxOccupancy,
        ?Cursor $cursor,
    ): PaginatedResult {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from(SegmentEntity::class, 's')
            ->innerJoin('s.itinerary', 'i')
            ->addSelect('i')
            ->innerJoin('i.route', 'r')
            ->addSelect('r')
            ->orderBy('s.position', 'ASC');

        if (null !== $itineraryId) {
            $qb->andWhere('i.id = :itineraryId')
                ->setParameter('itineraryId', $itineraryId);
        }

        if (null !== $routeId) {
            $qb->andWhere('r.id = :routeId')
                ->setParameter('routeId', $routeId);
        }

        if (null !== $modality) {
            $qb->andWhere('s.modality = :modality')
                ->setParameter('modality', $modality);
        }

        if (null !== $maxOccupancy) {
            $sub = $this->entityManager->createQueryBuilder()
                ->select('IDENTITY(regSub.segment)')
                ->from(RegistrationEntity::class, 'regSub')
                ->innerJoin('regSub.segment', 'sSub')
                ->where('sSub.capacity IS NOT NULL')
                ->andWhere('sSub.id = s.id')
                ->groupBy('regSub.segment')
                ->having('(COUNT(regSub.id) * 100.0 / sSub.capacity) >= :maxOccupancy');

            $qb->andWhere($qb->expr()->exists($sub->getDQL()))
                ->setParameter('maxOccupancy', $maxOccupancy);
        }

        if (null !== $cursor) {
            $qb->andWhere('s.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        return $this->paginate(
            qb: $qb,
            countExpr: 'COUNT(s.id)',
            limit: $limit,
            toDto: $this->adminSegmentFactory->fromEntity(...),
            toCursorValue: fn (SegmentEntity $s) => (string) $s->id(),
        );
    }
}
