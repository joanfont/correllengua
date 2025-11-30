<?php

namespace App\Infrastructure\Doctrine\Provider\Participant;

use App\Domain\DTO\Participant\Participant;
use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Model\Participant\Participant as ParticipantModel;
use App\Domain\Provider\Participant\ParticipantProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use Doctrine\ORM\EntityManagerInterface;

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
}
