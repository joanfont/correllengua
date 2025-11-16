<?php

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

    public function findByCode(int $code): Route
    {
        $route = $this->entityManager->createQueryBuilder()
            ->select('r')
            ->from(Route::class, 'r')
            ->where('r.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $route) {
            throw RouteNotFoundException::fromCode($code);
        }

        return $route;
    }
}
