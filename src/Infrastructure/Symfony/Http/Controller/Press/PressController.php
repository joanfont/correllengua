<?php

namespace App\Infrastructure\Symfony\Http\Controller\Press;

use App\Application\Command\Press\CreatePressNote;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Press\ListPressNotes;
use App\Infrastructure\Symfony\Http\DTO\Press\CreatePressNoteRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/press')]
class PressController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function listPressNotes(QueryBus $queryBus): Response
    {
        $listPressNotes = new ListPressNotes();
        $pressNotes = $queryBus->query($listPressNotes);

        return $this->json($pressNotes);
    }

    #[Route('', methods: ['POST'])]
    public function createPressNote(
        CommandBus $commandBus,
        CreatePressNoteRequest $createPressNoteRequest,
    ): Response {
        $createPressNote = new CreatePressNote(
            title: $createPressNoteRequest->title,
            subtitle: $createPressNoteRequest->subtitle,
            body: $createPressNoteRequest->body,
            featured: $createPressNoteRequest->featured,
            image: $createPressNoteRequest->image,
        );

        $commandBus->dispatch($createPressNote);

        return new Response(null, Response::HTTP_CREATED);
    }
}
