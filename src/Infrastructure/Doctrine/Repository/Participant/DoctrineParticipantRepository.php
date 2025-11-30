<?php

namespace App\Infrastructure\Doctrine\Repository\Participant;

use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Repository\Participant\ParticipantRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineParticipantRepository extends DoctrineRepository implements ParticipantRepository
{
    public function add(Participant $participant): void
    {
        $this->entityManager->persist($participant);
    }

    public function findById(ParticipantId $id): Participant
    {
        $participant = $this->entityManager->find(Participant::class, (string) $id);
        if (null === $participant) {
            throw ParticipantNotFoundException::fromId($id);
        }

        return $participant;
    }

    public function findByEmail(string $email): Participant
    {
        /** @var ?Participant $participant */
        $participant = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Participant::class, 'p')
            ->leftJoin('p.registrations', 'r')
            ->where('p.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $participant) {
            throw new ParticipantNotFoundException($email);
        }

        return $participant;
    }

    public function existsByEmail(string $email): bool
    {
        try {
            $this->findByEmail($email);

            return true;
        } catch (ParticipantNotFoundException) {
            return false;
        }
    }
}
