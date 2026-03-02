<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Admin;

use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Participant\ListParticipants;
use App\Domain\DTO\Common\Cursor;
use App\Infrastructure\Nelmio\Operation\Admin\ListParticipantsOperation;
use App\Infrastructure\Nelmio\Tag\AdminTag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/participants')]
#[AdminTag]
#[IsGranted('IS_AUTHENTICATED')]
final class ParticipantController extends AbstractController
{
    #[Route('', name: 'admin_list_participants', methods: ['GET'])]
    #[ListParticipantsOperation]
    public function list(
        QueryBus $queryBus,
        #[MapQueryParameter]
        ?string $routeId = null,
        #[MapQueryParameter]
        ?string $itineraryId = null,
        #[MapQueryParameter]
        ?string $segmentId = null,
        #[MapQueryParameter]
        ?int $maxOccupancy = null,
        #[MapQueryParameter]
        int $limit = 20,
        #[MapQueryParameter]
        ?string $cursor = null,
    ): JsonResponse {
        $result = $queryBus->query(new ListParticipants(
            routeId: $routeId,
            itineraryId: $itineraryId,
            segmentId: $segmentId,
            maxOccupancy: $maxOccupancy,
            limit: $limit,
            cursor: null !== $cursor ? Cursor::fromEncoded($cursor) : null,
        ));

        return $this->json($result);
    }
}
