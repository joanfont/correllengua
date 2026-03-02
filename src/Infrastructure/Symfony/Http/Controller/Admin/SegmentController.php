<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Admin;

use App\Application\Command\Route\Admin\CreateSegment;
use App\Application\Command\Route\Admin\UpdateSegment;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Route\Admin\ListSegments;
use App\Domain\DTO\Common\Cursor;
use App\Infrastructure\Nelmio\Operation\Admin\CreateSegmentOperation;
use App\Infrastructure\Nelmio\Operation\Admin\ListAdminSegmentsOperation;
use App\Infrastructure\Nelmio\Operation\Admin\UpdateSegmentOperation;
use App\Infrastructure\Nelmio\Tag\AdminTag;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\CreateSegmentRequest;
use App\Infrastructure\Symfony\Http\DTO\Admin\Request\UpdateSegmentRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/segments')]
#[AdminTag]
#[IsGranted('IS_AUTHENTICATED')]
final class SegmentController extends AbstractController
{
    #[Route('', name: 'admin_list_segments', methods: ['GET'])]
    #[ListAdminSegmentsOperation]
    public function list(
        QueryBus $queryBus,
        #[MapQueryParameter]
        ?string $itineraryId = null,
        #[MapQueryParameter]
        ?string $routeId = null,
        #[MapQueryParameter]
        ?string $modality = null,
        #[MapQueryParameter]
        int $limit = 20,
        #[MapQueryParameter]
        ?string $cursor = null,
    ): JsonResponse {
        $result = $queryBus->query(new ListSegments(
            itineraryId: $itineraryId,
            routeId: $routeId,
            modality: $modality,
            limit: $limit,
            cursor: null !== $cursor ? Cursor::fromEncoded($cursor) : null,
        ));

        return $this->json($result);
    }

    #[Route('', name: 'admin_create_segment', methods: ['POST'])]
    #[CreateSegmentOperation]
    public function create(
        CommandBus $commandBus,
        #[MapRequestPayload]
        CreateSegmentRequest $request,
    ): Response {
        $commandBus->dispatch(new CreateSegment(
            itineraryId: $request->itineraryId,
            position: $request->position,
            startLatitude: $request->startLatitude,
            startLongitude: $request->startLongitude,
            endLatitude: $request->endLatitude,
            endLongitude: $request->endLongitude,
            capacity: $request->capacity,
            modality: $request->modality,
            startTime: $request->startTime,
        ));

        return new Response(null, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'admin_update_segment', methods: ['PUT'])]
    #[UpdateSegmentOperation]
    public function update(
        CommandBus $commandBus,
        string $id,
        #[MapRequestPayload]
        UpdateSegmentRequest $request,
    ): Response {
        $commandBus->dispatch(new UpdateSegment(
            id: $id,
            position: $request->position,
            startLatitude: $request->startLatitude,
            startLongitude: $request->startLongitude,
            endLatitude: $request->endLatitude,
            endLongitude: $request->endLongitude,
            capacity: $request->capacity,
            modality: $request->modality,
            startTime: $request->startTime,
        ));

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
