<?php

namespace App\Infrastructure\Symfony\Http\Controller\Route;

use App\Application\Command\Participant\CreateParticipant;
use App\Application\Command\Registration\RegisterParticipant;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Participant\GetParticipantByEmail;
use App\Application\Query\Route\ListRoutes;
use App\Domain\Exception\Participant\ParticipantAlreadyExistsException;
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
        QueryBus $queryBus,
        string $segmentId,
        #[MapRequestPayload]
        RegisterParticipantRequest $registerParticipantRequest,
    ): Response {
        $createParticipant = new CreateParticipant(
            name: $registerParticipantRequest->name,
            surname: $registerParticipantRequest->surname,
            email: $registerParticipantRequest->email,
        );

        try {
            $commandBus->dispatch($createParticipant);
        } catch (ParticipantAlreadyExistsException) {
            // Ignore exception as participant already exists
        } finally {
            $getParticipantByEmail = new GetParticipantByEmail($registerParticipantRequest->email);
            $participant = $queryBus->query($getParticipantByEmail);
        }

        $registerParticipant = new RegisterParticipant(
            participantId: $participant->id,
            segmentId: $segmentId,
            modality: $registerParticipantRequest->modality,
        );

        $commandBus->dispatch($registerParticipant);

        return new Response(null, Response::HTTP_CREATED);
    }
}
