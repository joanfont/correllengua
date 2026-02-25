<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\Route;

use App\Domain\Exception\Route\RouteNotFoundException;
use App\Domain\Model\Route\Route;
use App\Domain\Repository\Route\RouteRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineRouteRepository extends DoctrineRepository implements RouteRepository
{
    public function add(Route $route): void
    {
        $this->entityManager->persist($route);
    }

    public function findByName(string $name): Route
    {
        /** @var ?Route $route */
        $route = $this->entityManager->createQueryBuilder()
            ->select('r')
            ->from(Route::class, 'r')
            ->where('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $route) {
            throw RouteNotFoundException::fromName($name);
        }

        return $route;
    }

    public function deleteAll(): void
    {
        $this->entityManager->createQueryBuilder()
            ->delete(Route::class, 'r')
            ->getQuery()
            ->execute();
    }
}
