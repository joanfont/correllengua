<?php

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Route\Route;
use App\Domain\Model\Route\Itinerary as ItineraryModel;
use App\Domain\Model\Route\Route as RouteModel;
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

    public function findAll(): array
    {
        $routes = $this->entityManager->createQueryBuilder()
            ->select('r', 's')
            ->from(RouteModel::class, 'r')
            ->leftJoin('r.segments', 's')
            ->leftJoin('r.itineraries', 'i')
            ->getQuery()
            ->getResult();

        return array_map(
            $this->buildRoute(...),
            $routes,
        );
    }

    private function buildRoute(RouteModel $route): Route
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
