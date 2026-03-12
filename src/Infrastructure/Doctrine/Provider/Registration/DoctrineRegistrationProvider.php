<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Registration;

use App\Domain\DTO\Registration\Registration;
use App\Domain\Exception\Registration\RegistrationNotFoundException;
use App\Domain\Model\Registration\Registration as RegistrationEntity;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Provider\Registration\RegistrationProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use App\Infrastructure\Doctrine\Provider\Participant\ParticipantFactory;
use App\Infrastructure\Doctrine\Provider\Route\SegmentFactory;

use function array_map;

use Doctrine\ORM\EntityManagerInterface;

class DoctrineRegistrationProvider extends DoctrineProvider implements RegistrationProvider
{
    public function __construct(
        private readonly ParticipantFactory $participantFactory,
        private readonly SegmentFactory $segmentFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    public function findById(string $id): Registration
    {
        /** @var ?RegistrationEntity $registration */
        $registration = $this->entityManager->createQueryBuilder()
            ->select('r', 's', 'p', 'i', 'ro')
            ->from(RegistrationEntity::class, 'r')
            ->innerJoin('r.segment', 's')
            ->innerJoin('r.participant', 'p')
            ->innerJoin('s.itinerary', 'i')
            ->innerJoin('i.route', 'ro')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $registration) {
            throw RegistrationNotFoundException::fromId(RegistrationId::from($id));
        }

        return $this->toDto($registration);
    }

    public function findByParticipantIdAndSegmentIds(string $participantId, array $segmentIds): array
    {
        /** @var array<RegistrationEntity> $registrations */
        $registrations = $this->entityManager->createQueryBuilder()
            ->select('r', 's', 'p', 'i', 'ro')
            ->from(RegistrationEntity::class, 'r')
            ->innerJoin('r.segment', 's')
            ->innerJoin('r.participant', 'p')
            ->innerJoin('s.itinerary', 'i')
            ->innerJoin('i.route', 'ro')
            ->where('p.id = :participantId')
            ->andWhere('s.id IN (:segmentIds)')
            ->setParameter('participantId', $participantId)
            ->setParameter('segmentIds', $segmentIds)
            ->orderBy('ro.startsAt', 'ASC')
            ->addOrderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map($this->toDto(...), $registrations);
    }

    private function toDto(RegistrationEntity $registration): Registration
    {
        return new Registration(
            id: (string) $registration->id(),
            participant: $this->participantFactory->fromEntity($registration->participant()),
            segment: $this->segmentFactory->fromEntity($registration->segment()),
            hash: $registration->hash(),
        );
    }
}
