<?php

namespace App\Infrastructure\Doctrine\Repository\Registration;

use App\Domain\Exception\Registration\RegistrationNotFoundException;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Repository\Registration\RegistrationRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineRegistrationRepository extends DoctrineRepository implements RegistrationRepository
{
    public function findById(RegistrationId $id): Registration
    {
        $registration = $this->entityManager->find(Registration::class, (string) $id);

        if (null === $registration) {
            throw RegistrationNotFoundException::fromId($id);
        }

        return $registration;
    }

    public function findByHash(string $hash): Registration
    {
        $registration = $this->entityManager->createQueryBuilder()
            ->select('r')
            ->from(Registration::class, 'r')
            ->where('r.id = :id')
            ->setParameter('id', $hash)
            ->getQuery()
            ->getSingleResult();

        if (null === $registration) {
            throw RegistrationNotFoundException::fromHash($hash);
        }

        return $registration;
    }

    public function delete(Registration $registration): void
    {
        // TODO: Implement delete() method.
    }
}
