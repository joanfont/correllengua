<?php

namespace App\Infrastructure\Symfony\Http\Controller\Route;

use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Route\ListRoutes;
use App\Infrastructure\Nelmio\Operation\Route\ListRoutesOperation;
use App\Infrastructure\Nelmio\Tag\RoutesTag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/route')]
#[RoutesTag]
final class RouteController extends AbstractController
{
    #[Route('', name: 'list_routes', methods: ['GET'])]
    #[ListRoutesOperation]
    public function listAll(QueryBus $queryBus): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $listRoutes = new ListRoutes();
        $routes = $queryBus->query($listRoutes);

        return $this->json($routes);
    }
}
