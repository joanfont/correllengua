<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Participant;

use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\DTO\Participant\Participant;
use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Model\Participant\Participant as ParticipantModel;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\RouteId;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Provider\Participant\ParticipantProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;

use function array_map;
use function array_pop;
use function count;

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

        if (null !== $cursor) {
            $qb->andWhere('p.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        $countQb = clone $qb;
        $countQb->select('COUNT(DISTINCT p.id)');
        $total = (int) $countQb->getQuery()->getSingleScalarResult();

        $qb->setMaxResults($limit + 1);

        /** @var array<ParticipantModel> $participants */
        $participants = $qb->getQuery()->getResult();

        $hasNextPage = count($participants) > $limit;
        if ($hasNextPage) {
            array_pop($participants);
        }

        $items = array_map(
            fn (ParticipantModel $participant) => $this->participantFactory->fromEntity($participant),
            $participants,
        );

        $nextCursor = null;
        if ($hasNextPage && count($participants) > 0) {
            $lastParticipant = $participants[count($participants) - 1];
            $nextCursor = Cursor::fromValue((string) $lastParticipant->id());
        }

        return new PaginatedResult(
            items: $items,
            total: $total,
            nextCursor: $nextCursor,
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
}
