<?php

namespace App\Infrastructure\Symfony\Http\Controller\Press;

use App\Application\Command\Press\CreatePressNote;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Press\ListPressNotes;
use App\Infrastructure\Nelmio\Operation\Press\CreatePressNoteOperation;
use App\Infrastructure\Nelmio\Operation\Press\ListPressNotesOperation;
use App\Infrastructure\Nelmio\Tag\PressTag;
use App\Infrastructure\Symfony\Http\DTO\Press\CreatePressNoteRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/press')]
#[PressTag]
final class PressController extends AbstractController
{
    #[Route('', name: 'list_press_notes', methods: ['GET'])]
    #[ListPressNotesOperation]
    public function listPressNotes(QueryBus $queryBus): JsonResponse
    {
        $listPressNotes = new ListPressNotes();
        $pressNotes = $queryBus->query($listPressNotes);

        return $this->json($pressNotes);
    }

    #[Route('', name: 'create_press_note', methods: ['POST'])]
    #[CreatePressNoteOperation]
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
