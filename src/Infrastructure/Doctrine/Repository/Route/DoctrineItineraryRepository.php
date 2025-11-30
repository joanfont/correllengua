<?php

namespace App\Infrastructure\Doctrine\Repository\Route;

use App\Domain\Exception\Route\ItineraryNotFoundException;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Repository\Route\ItineraryRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineItineraryRepository extends DoctrineRepository implements ItineraryRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function add(Itinerary $itinerary): void
    {
        $this->entityManager->persist($itinerary);
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
}
