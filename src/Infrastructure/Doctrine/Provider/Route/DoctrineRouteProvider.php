<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Route\Route;
use App\Domain\Model\Route\Route as RouteEntity;
use App\Domain\Provider\Route\RouteProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;

use function array_map;

use Doctrine\ORM\EntityManagerInterface;

class DoctrineRouteProvider extends DoctrineProvider implements RouteProvider
{
    public function __construct(
        private readonly ItineraryFactory $itineraryFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @return array<Route>
     */
    public function findAll(): array
    {
        /** @var array<RouteEntity> $routes */
        $routes = $this->entityManager->createQueryBuilder()
            ->select('r', 'i', 's')
            ->from(RouteEntity::class, 'r')
            ->leftJoin('r.itineraries', 'i')
            ->leftJoin('i.segments', 's')
            ->getQuery()
            ->getResult();

        return array_map(
            $this->buildRoute(...),
            $routes,
        );
    }

    private function buildRoute(RouteEntity $route): Route
    {
        return new Route(
            id: (string) $route->id(),
            name: $route->name(),
            itineraries: array_map(
                $this->itineraryFactory->fromEntity(...),
                $route->itineraries(),
            ),
        );
    }
}
