<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminSegment;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Model\Route\Segment as SegmentEntity;
use App\Domain\Provider\Route\SegmentProvider;
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
