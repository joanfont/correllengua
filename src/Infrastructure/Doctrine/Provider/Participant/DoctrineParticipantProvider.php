<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Participant;

use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\DTO\Participant\Participant;
use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Model\Participant\Participant as ParticipantModel;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\RouteId;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Provider\Participant\ParticipantProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineParticipantProvider extends DoctrineProvider implements ParticipantProvider
{
    public function __construct(
        private readonly ParticipantFactory $participantFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    public function findByEmail(string $email): Participant
    {
        $participant = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(ParticipantModel::class, 'p')
            ->where('p.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $participant) {
            throw ParticipantNotFoundException::fromEmail($email);
        }

        /* @var ParticipantModel $participant */
        return $this->participantFactory->fromEntity($participant);
    }

    public function findAllPaginated(
        ?RouteId $routeId,
        ?ItineraryId $itineraryId,
        ?SegmentId $segmentId,
        ?int $maxOccupancy,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(ParticipantModel::class, 'p')
            ->leftJoin('p.registrations', 'reg')
            ->addSelect('reg')
            ->leftJoin('reg.segment', 's')
            ->addSelect('s')
            ->leftJoin('s.itinerary', 'i')
            ->addSelect('i')
            ->leftJoin('i.route', 'r')
            ->addSelect('r')
            ->orderBy('p.id', 'ASC');

        $this->applyFilters($qb, $routeId, $itineraryId, $segmentId);
        $this->applyOccupancyFilter($qb, $maxOccupancy);

        if (null !== $cursor) {
            $qb->andWhere('p.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        return $this->paginate(
            qb: $qb,
            countExpr: 'COUNT(DISTINCT p.id)',
            limit: $limit,
            toDto: fn (ParticipantModel $p) => $this->participantFactory->fromEntity($p),
            toCursorValue: fn (ParticipantModel $p) => (string) $p->id(),
        );
    }

    private function applyFilters(
        QueryBuilder $qb,
        ?RouteId $routeId,
        ?ItineraryId $itineraryId,
        ?SegmentId $segmentId,
    ): void {
        if (null !== $segmentId) {
            $qb->andWhere('s.id = :segmentId')
                ->setParameter('segmentId', (string) $segmentId);
        } elseif (null !== $itineraryId) {
            $qb->andWhere('i.id = :itineraryId')
                ->setParameter('itineraryId', (string) $itineraryId);
        } elseif (null !== $routeId) {
            $qb->andWhere('r.id = :routeId')
                ->setParameter('routeId', (string) $routeId);
        }

        $qb->distinct();
    }

    private function applyOccupancyFilter(QueryBuilder $qb, ?int $maxOccupancy): void
    {
        if (null === $maxOccupancy) {
            return;
        }

        // Subquery: segment IDs whose (enrolments / capacity * 100) >= threshold
        $sub = $this->entityManager->createQueryBuilder()
            ->select('IDENTITY(regSub.segment)')
            ->from(Registration::class, 'regSub')
            ->innerJoin('regSub.segment', 'sSub')
            ->where('sSub.capacity IS NOT NULL')
            ->groupBy('regSub.segment')
            ->having('(COUNT(regSub.id) * 100.0 / sSub.capacity) >= :maxOccupancy');

        $qb->andWhere($qb->expr()->in('s.id', $sub->getDQL()))
            ->setParameter('maxOccupancy', $maxOccupancy);
    }
}
