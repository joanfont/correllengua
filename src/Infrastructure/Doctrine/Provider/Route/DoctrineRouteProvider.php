<?php

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Route\Route;
use App\Domain\Model\Route\Route as RouteModel;
use App\Domain\Model\Route\Segment as SegmentModel;
use App\Domain\Provider\Route\RouteProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineRouteProvider extends DoctrineProvider implements RouteProvider
{
    public function __construct(
        private readonly SegmentFactory $segmentFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    public function findAll(): array
    {
        $routes = $this->entityManager->createQueryBuilder()
            ->select('r', 's')
            ->from(RouteModel::class, 'r')
            ->leftJoin('r.segments', 's')
            ->getQuery()
            ->getResult();

        return array_map(
            fn (RouteModel $route) => $this->buildRoute($route),
            $routes
        );
    }

    private function buildRoute(RouteModel $route): Route
    {
        return new Route(
            (string) $route->id(),
            $route->name(),
            array_map(fn (SegmentModel $segment) => $this->segmentFactory->fromEntity($segment), $route->segments()),
        );
    }
}
