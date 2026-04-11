<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Admin;

use App\Application\Command\Route\Admin\CreateItinerary;
use App\Application\Command\Route\Admin\UpdateItinerary;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Route\Admin\ListItineraries;
use App\Domain\DTO\Common\Cursor;
use App\Infrastructure\Nelmio\Operation\Admin\CreateItineraryOperation;
use App\Infrastructure\Nelmio\Operation\Admin\ListAdminItinerariesOperation;
use App\Infrastructure\Nelmio\Operation\Admin\UpdateItineraryOperation;
use App\Infrastructure\Nelmio\Tag\AdminTag;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\CreateItineraryRequest;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\UpdateItineraryRequest;
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
        ?int $maxOccupancy = null,
        #[MapQueryParameter]
        ?string $cursor = null,
    ): JsonResponse {
        $result = $queryBus->query(new ListItineraries(
            name: $name,
            routeId: $routeId,
            limit: $limit,
            maxOccupancy: $maxOccupancy,
            cursor: null !== $cursor ? Cursor::fromEncoded($cursor) : null,
        ));

        return $this->json($result);
    }

    #[Route('', name: 'admin_create_itinerary', methods: ['POST'])]
    #[CreateItineraryOperation]
    public function create(
        CommandBus $commandBus,
        #[MapRequestPayload]
        CreateItineraryRequest $request,
    ): Response {
        $commandBus->dispatch(new CreateItinerary(
            routeId: $request->routeId,
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
