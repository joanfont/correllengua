<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Admin;

use App\Application\Command\Route\Admin\CreateRoute;
use App\Application\Command\Route\Admin\UpdateRoute;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Route\Admin\ListRoutes;
use App\Domain\DTO\Admin\Route\AdminRoute;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Infrastructure\Nelmio\Operation\Admin\CreateRouteOperation;
use App\Infrastructure\Nelmio\Operation\Admin\ListAdminRoutesOperation;
use App\Infrastructure\Nelmio\Operation\Admin\UpdateRouteOperation;
use App\Infrastructure\Nelmio\Tag\AdminTag;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\CreateRouteRequest;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\UpdateRouteRequest;
use App\Infrastructure\Symfony\Http\DTO\Admin\Response\PaginatedRoutesResponse;
use App\Infrastructure\Symfony\Http\DTO\Common\CursorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/routes')]
#[AdminTag]
#[IsGranted('IS_AUTHENTICATED')]
final class RouteController extends AbstractController
{
    #[Route('', name: 'admin_list_routes', methods: ['GET'])]
    #[ListAdminRoutesOperation]
    public function list(
        QueryBus $queryBus,
        #[MapQueryParameter]
        ?string $name = null,
        #[MapQueryParameter]
        int $limit = 20,
        #[MapQueryParameter]
        ?string $cursor = null,
    ): JsonResponse {
        /** @var PaginatedResult<AdminRoute> $result */
        $result = $queryBus->query(new ListRoutes(
            name: $name,
            limit: $limit,
            cursor: null !== $cursor ? Cursor::fromEncoded($cursor) : null,
        ));

        return $this->json(new PaginatedRoutesResponse(
            items: $result->items,
            cursor: new CursorResponse(
                next: $result->nextCursor?->encode(),
                previous: null,
            ),
            total: $result->total,
        ));
    }

    #[Route('', name: 'admin_create_route', methods: ['POST'])]
    #[CreateRouteOperation]
    public function create(
        CommandBus $commandBus,
        #[MapRequestPayload]
        CreateRouteRequest $request,
    ): Response {
        $commandBus->dispatch(new CreateRoute(
            name: $request->name,
            description: $request->description,
            position: $request->position,
            startsAt: $request->starts_at,
        ));

        return new Response(null, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'admin_update_route', methods: ['PUT'])]
    #[UpdateRouteOperation]
    public function update(
        CommandBus $commandBus,
        string $id,
        #[MapRequestPayload]
        UpdateRouteRequest $request,
    ): Response {
        $commandBus->dispatch(new UpdateRoute(
            id: $id,
            name: $request->name,
            description: $request->description,
            position: $request->position,
            startsAt: $request->starts_at,
        ));

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
