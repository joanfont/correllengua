<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Registration;

use App\Application\Command\Registration\DeregisterParticipant;
use App\Application\Command\Registration\DTO\Participant;
use App\Application\Command\Registration\RegisterParticipant;
use App\Application\Commons\Command\CommandBus;
use App\Infrastructure\Nelmio\Operation\Registration\DeregisterParticipantOperation;
use App\Infrastructure\Nelmio\Operation\Registration\RegisterParticipantOperation;
use App\Infrastructure\Nelmio\Tag\RegistrationTag;
use App\Infrastructure\Symfony\Http\DTO\Route\RegisterParticipantRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/registration')]
#[RegistrationTag]
final class RegistrationController extends AbstractController
{
    #[Route('', name: 'register_participant', methods: ['POST'])]
    #[RegisterParticipantOperation]
    public function registerParticipant(
        CommandBus $commandBus,
        #[MapRequestPayload]
        RegisterParticipantRequest $registerParticipantRequest,
    ): Response {
        $participant = new Participant(
            name: $registerParticipantRequest->participant->name,
            surname: $registerParticipantRequest->participant->surname,
            email: $registerParticipantRequest->participant->email,
        );

        $registerParticipant = new RegisterParticipant(
            participant: $participant,
            segments: $registerParticipantRequest->segments,
            modality: $registerParticipantRequest->modality,
        );

        $commandBus->dispatch($registerParticipant);

        return new Response(null, Response::HTTP_CREATED);
    }

    #[Route('/deregister', name: 'deregister_participant', methods: ['GET'])]
    #[DeregisterParticipantOperation]
    public function deregisterParticipant(
        CommandBus $commandBus,
        #[MapQueryParameter('hash')]
        string $registrationHash,
    ): Response {
        $deregisterParticipant = new DeregisterParticipant($registrationHash);
        $commandBus->dispatch($deregisterParticipant);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
