<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Admin;

use App\Application\Command\Route\Admin\CreateItinerary;
use App\Application\Command\Route\Admin\UpdateItinerary;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Route\Admin\ListItineraries;
use App\Domain\DTO\Admin\Route\AdminItinerary;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Infrastructure\Nelmio\Operation\Admin\CreateItineraryOperation;
use App\Infrastructure\Nelmio\Operation\Admin\ListAdminItinerariesOperation;
use App\Infrastructure\Nelmio\Operation\Admin\UpdateItineraryOperation;
use App\Infrastructure\Nelmio\Tag\AdminTag;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\CreateItineraryRequest;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\UpdateItineraryRequest;
use App\Infrastructure\Symfony\Http\DTO\Admin\Response\PaginatedItinerariesResponse;
use App\Infrastructure\Symfony\Http\DTO\Common\CursorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/itineraries')]
#[AdminTag]
#[IsGranted('IS_AUTHENTICATED')]
final class ItineraryController extends AbstractController
{
    #[Route('', name: 'admin_list_itineraries', methods: ['GET'])]
    #[ListAdminItinerariesOperation]
    public function list(
        QueryBus $queryBus,
        #[MapQueryParameter]
        ?string $name = null,
        #[MapQueryParameter]
        ?string $routeId = null,
        #[MapQueryParameter]
        int $limit = 20,
        #[MapQueryParameter]
        ?string $cursor = null,
    ): JsonResponse {
        /** @var PaginatedResult<AdminItinerary> $result */
        $result = $queryBus->query(new ListItineraries(
            name: $name,
            routeId: $routeId,
            limit: $limit,
            cursor: null !== $cursor ? Cursor::fromEncoded($cursor) : null,
        ));

        return $this->json(new PaginatedItinerariesResponse(
            items: $result->items,
            cursor: new CursorResponse(
                next: $result->nextCursor?->encode(),
                previous: null,
            ),
            total: $result->total,
        ));
    }

    #[Route('', name: 'admin_create_itinerary', methods: ['POST'])]
    #[CreateItineraryOperation]
    public function create(
        CommandBus $commandBus,
        #[MapRequestPayload]
        CreateItineraryRequest $request,
    ): Response {
        $commandBus->dispatch(new CreateItinerary(
            routeId: $request->route_id,
            name: $request->name,
            position: $request->position,
        ));

        return new Response(null, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'admin_update_itinerary', methods: ['PUT'])]
    #[UpdateItineraryOperation]
    public function update(
        CommandBus $commandBus,
        string $id,
        #[MapRequestPayload]
        UpdateItineraryRequest $request,
    ): Response {
        $commandBus->dispatch(new UpdateItinerary(
            id: $id,
            name: $request->name,
            position: $request->position,
        ));

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
