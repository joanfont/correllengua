<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminItinerary;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Model\Route\Itinerary as ItineraryEntity;
use App\Domain\Provider\Route\ItineraryProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineItineraryProvider extends DoctrineProvider implements ItineraryProvider
{
    public function __construct(
        private readonly ItineraryFactory $adminItineraryFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @return PaginatedResult<AdminItinerary>
     */
    public function findAllPaginated(
        ?string $name,
        ?string $routeId,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('i')
            ->from(ItineraryEntity::class, 'i')
            ->innerJoin('i.route', 'r')
            ->addSelect('r')
            ->orderBy('i.position', 'ASC');

        if (null !== $name) {
            $qb->andWhere('i.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if (null !== $routeId) {
            $qb->andWhere('r.id = :routeId')
                ->setParameter('routeId', $routeId);
        }

        if (null !== $cursor) {
            $qb->andWhere('i.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        return $this->paginate(
            qb: $qb,
            countExpr: 'COUNT(i.id)',
            limit: $limit,
            toDto: $this->adminItineraryFactory->fromEntity(...),
            toCursorValue: fn (ItineraryEntity $i) => (string) $i->id(),
        );
    }
}
