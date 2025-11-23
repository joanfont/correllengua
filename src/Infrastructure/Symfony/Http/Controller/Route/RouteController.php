<?php

namespace App\Infrastructure\Symfony\Http\Controller\Route;

use App\Application\Command\Registration\DTO\Participant;
use App\Application\Command\Registration\RegisterParticipant;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Route\ListRoutes;
use App\Infrastructure\Symfony\Http\DTO\Route\RegisterParticipantRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

    #[Route('/{routeId}/segment/{segmentId}/register', methods: ['POST'])]
    public function registerParticipant(
        CommandBus $commandBus,
        string $segmentId,
        #[MapRequestPayload]
        RegisterParticipantRequest $registerParticipantRequest,
    ): Response {
        $participant = new Participant(
            name: $registerParticipantRequest->name,
            surname: $registerParticipantRequest->surname,
            email: $registerParticipantRequest->email,
        );

        $registerParticipant = new RegisterParticipant(
            participant: $participant,
            segmentId: $segmentId,
            modality: $registerParticipantRequest->modality,
        );

        $commandBus->dispatch($registerParticipant);

        return new Response(null, Response::HTTP_CREATED);
    }
}
