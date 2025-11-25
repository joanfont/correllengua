<?php

namespace App\Infrastructure\Symfony\Http\Controller\Registration;

use App\Application\Command\Registration\DTO\Participant;
use App\Application\Command\Registration\RegisterParticipant;
use App\Application\Commons\Command\CommandBus;
use App\Infrastructure\Symfony\Http\DTO\Route\RegisterParticipantRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    public function registerParticipant(CommandBus $commandBus, RegisterParticipantRequest $registerParticipantRequest): Response
    {
        $participant = new Participant(
            name: $registerParticipantRequest->participant->name,
            surname: $registerParticipantRequest->participant->surname,
            email: $registerParticipantRequest->participant->email,
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