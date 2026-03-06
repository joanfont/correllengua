<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route\Admin;

use App\Domain\DTO\Admin\Route\AdminItinerary;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Model\Registration\Registration as RegistrationEntity;
use App\Domain\Model\Route\Itinerary as ItineraryEntity;
use App\Domain\Provider\Route\Admin\ItineraryProvider;
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
        ?int $maxOccupancy,
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

        if (null !== $maxOccupancy) {
            $sub = $this->entityManager->createQueryBuilder()
                ->select('IDENTITY(regSub.segment)')
                ->from(RegistrationEntity::class, 'regSub')
                ->innerJoin('regSub.segment', 'sSub')
                ->innerJoin('sSub.itinerary', 'iSub')
                ->where('sSub.capacity IS NOT NULL')
                ->andWhere('iSub.id = i.id')
                ->groupBy('regSub.segment')
                ->having('(COUNT(regSub.id) * 100.0 / sSub.capacity) >= :maxOccupancy');

            $qb->andWhere($qb->expr()->exists($sub->getDQL()))
                ->setParameter('maxOccupancy', $maxOccupancy);
        }

        if (null !== $cursor) {
            $qb->andWhere('i.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        return $this->paginate(
            qb: $qb,
            countExpr: 'COUNT(i.id)',
            limit: $limit,
            toDto: function (ItineraryEntity $i): AdminItinerary {
                return $this->adminItineraryFactory->fromEntity($i, $this->countEnrolmentsForItinerary((string) $i->id()));
            },
            toCursorValue: fn (ItineraryEntity $i) => (string) $i->id(),
        );
    }

    private function countEnrolmentsForItinerary(string $itineraryId): int
    {
        return (int) $this->entityManager->createQueryBuilder()
            ->select('COUNT(reg.id)')
            ->from(RegistrationEntity::class, 'reg')
            ->innerJoin('reg.segment', 's')
            ->innerJoin('s.itinerary', 'i')
            ->where('i.id = :itineraryId')
            ->setParameter('itineraryId', $itineraryId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
