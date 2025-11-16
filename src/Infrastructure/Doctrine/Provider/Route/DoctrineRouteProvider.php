<?php

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Route\Route;
use App\Domain\DTO\Route\Segment;
use App\Domain\Model\Route\Route as RouteModel;
use App\Domain\Model\Route\Segment as SegmentModel;
use App\Domain\Provider\Route\RouteProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;

class DoctrineRouteProvider extends DoctrineProvider implements RouteProvider
{
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
            array_map(fn (SegmentModel $segment) => $this->buildSegment($segment), $route->segments()),
        );
    }

    private function buildSegment(SegmentModel $segment): Segment
    {
        return new Segment(
            (string) $segment->id(),
            new Coordinates($segment->start()->latitude(), $segment->start()->longitude()),
            new Coordinates($segment->end()->latitude(), $segment->end()->longitude()),
            $segment->capacity(),
            $segment->modality()->value
        );
    }
}
