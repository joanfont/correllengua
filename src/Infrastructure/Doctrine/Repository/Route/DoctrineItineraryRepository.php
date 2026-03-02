<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\Route;

use App\Domain\Exception\Route\ItineraryNotFoundException;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineItineraryRepository extends DoctrineRepository implements ItineraryRepository
{
    public function add(Itinerary $itinerary): void
    {
        $this->entityManager->persist($itinerary);
    }

    public function findById(ItineraryId $id): Itinerary
    {
        /** @var ?Itinerary $itinerary */
        $itinerary = $this->entityManager->find(Itinerary::class, (string) $id);
        if (null === $itinerary) {
            throw ItineraryNotFoundException::fromId($id);
        }

        return $itinerary;
    }

    public function findByName(string $name): Itinerary
    {
        /** @var ?Itinerary $itinerary */
        $itinerary = $this->entityManager->createQueryBuilder()
            ->select('i')
            ->from(Itinerary::class, 'i')
            ->where('i.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
        if (null === $itinerary) {
            throw ItineraryNotFoundException::fromName($name);
        }

        return $itinerary;
    }

    public function deleteAll(): void
    {
        $this->entityManager->createQueryBuilder()
            ->delete(Itinerary::class, 'i')
            ->getQuery()
            ->execute();
    }
}
