<?php

declare(strict_types=1);

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

        /* @var Registration $registration */
        return $registration;
    }

    public function findByHash(string $hash): Registration
    {
        /** @var ?Registration $registration */
        $registration = $this->entityManager->createQueryBuilder()
            ->select('r')
            ->from(Registration::class, 'r')
            ->where('r.hash = :hash')
            ->setParameter('hash', $hash)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $registration) {
            throw RegistrationNotFoundException::fromHash($hash);
        }

        return $registration;
    }

    public function delete(Registration $registration): void
    {
        $this->entityManager->remove($registration);
    }
}
