<?php

namespace App\Infrastructure\Symfony\Http\Controller\Route;

use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Route\ListRoutes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/route')]
class RouteController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function listAll(QueryBus $queryBus): Response
    {
        $listRoutes = new ListRoutes();
        $routes = $queryBus->query($listRoutes);

        return $this->json($routes);
    }
}
