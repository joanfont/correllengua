<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Admin\Route\AdminRoute;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\DTO\Route\Route;
use App\Domain\Model\Route\Route as RouteEntity;
use App\Domain\Provider\Route\RouteProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use App\Infrastructure\Doctrine\Provider\Route\Admin\RouteFactory as AdminRouteFactory;

use function array_map;

use Doctrine\ORM\EntityManagerInterface;

class DoctrineRouteProvider extends DoctrineProvider implements RouteProvider
{
    public function __construct(
        private readonly AdminRouteFactory $adminRouteFactory,
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
            ->select('r')
            ->from(RouteEntity::class, 'r')
            ->innerJoin('r.itineraries', 'i')
            ->addSelect('i')
            ->innerJoin('i.segments', 's')
            ->addSelect('s')
            ->orderBy('r.position', 'ASC')
            ->addOrderBy('i.position', 'ASC')
            ->addOrderBy('s.position', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map($this->buildRoute(...), $routes);
    }

    /**
     * @return PaginatedResult<AdminRoute>
     */
    public function findAllPaginated(
        ?string $name,
        int $limit,
        ?Cursor $cursor,
    ): PaginatedResult {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('r')
            ->from(RouteEntity::class, 'r')
            ->orderBy('r.position', 'ASC');

        if (null !== $name) {
            $qb->andWhere('r.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if (null !== $cursor) {
            $qb->andWhere('r.id > :cursor')
                ->setParameter('cursor', $cursor->value());
        }

        return $this->paginate(
            qb: $qb,
            countExpr: 'COUNT(r.id)',
            limit: $limit,
            toDto: $this->adminRouteFactory->fromEntity(...),
            toCursorValue: fn (RouteEntity $r) => (string) $r->id(),
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
