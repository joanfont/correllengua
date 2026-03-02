<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Admin;

use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Admin\GetStats;
use App\Infrastructure\Nelmio\Operation\Admin\GetStatsOperation;
use App\Infrastructure\Nelmio\Tag\AdminTag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/stats')]
#[AdminTag]
#[IsGranted('IS_AUTHENTICATED')]
final class StatsController extends AbstractController
{
    #[Route('', name: 'admin_stats', methods: ['GET'])]
    #[GetStatsOperation]
    public function __invoke(
        QueryBus $queryBus,
        #[MapQueryParameter]
        ?string $routeId = null,
        #[MapQueryParameter]
        ?string $itineraryId = null,
        #[MapQueryParameter]
        ?string $segmentId = null,
    ): JsonResponse {
        $stats = $queryBus->query(new GetStats(
            routeId: $routeId,
            itineraryId: $itineraryId,
            segmentId: $segmentId,
        ));

        return $this->json($stats);
    }
}
