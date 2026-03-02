<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminRoute;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Model\Registration\Registration as RegistrationEntity;
use App\Domain\Model\Route\Route as RouteEntity;
use App\Domain\Provider\Route\Admin\RouteProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineAdminRouteProvider extends DoctrineProvider implements RouteProvider
{
    public function __construct(
        private readonly RouteFactory $adminRouteFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @return PaginatedResult<AdminRoute>
     */
    public function findAllPaginated(
        ?string $name,
        int $limit,
        ?int $maxOccupancy,
        ?Cursor $cursor,
    ): PaginatedResult {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('r')
            ->from(RouteEntity::class, 'r')
            ->orderBy('r.position', 'ASC');

        if (null !== $name) {
            $qb->andWhere('r.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if (null !== $maxOccupancy) {
            $sub = $this->entityManager->createQueryBuilder()
                ->select('IDENTITY(regSub.segment)')
                ->from(RegistrationEntity::class, 'regSub')
                ->innerJoin('regSub.segment', 'sSub')
                ->innerJoin('sSub.itinerary', 'iSub')
                ->innerJoin('iSub.route', 'rSub')
                ->where('sSub.capacity IS NOT NULL')
                ->andWhere('rSub.id = r.id')
                ->groupBy('regSub.segment')
                ->having('(COUNT(regSub.id) * 100.0 / sSub.capacity) >= :maxOccupancy');

            $qb->andWhere($qb->expr()->exists($sub->getDQL()))
                ->setParameter('maxOccupancy', $maxOccupancy);
        }

        if (null !== $cursor) {
            $qb->andWhere('r.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        return $this->paginate(
            qb: $qb,
            countExpr: 'COUNT(r.id)',
            limit: $limit,
            toDto: $this->adminRouteFactory->fromEntity(...),
            toCursorValue: fn (RouteEntity $r) => (string) $r->id(),
        );
    }
}
