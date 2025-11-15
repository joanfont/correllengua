<?php

namespace App\Infrastructure\Doctrine\Provider\Participant;

use App\Domain\DTO\Participant\Participant;
use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Model\Participant\Participant as ParticipantModel;
use App\Domain\Provider\Participant\ParticipantProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;

class DoctrineParticipantProvider extends DoctrineProvider implements ParticipantProvider
{
    public function findByEmail(string $email): Participant
    {
        /** @var ParticipantModel $participant */
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

        return new Participant(
            id: $participant->id(),
            name: $participant->name(),
            surname: $participant->surname(),
            email: $participant->email(),
        );
    }
}