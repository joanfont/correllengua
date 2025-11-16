<?php

namespace App\Infrastructure\Doctrine\Repository\Route;

use App\Domain\Model\Route\Route;
use App\Domain\Repository\Route\RouteRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineRouteRepository extends DoctrineRepository implements RouteRepository {

    public function add(Route $route): void
    {
        $this->entityManager->persist($route);
    }
}