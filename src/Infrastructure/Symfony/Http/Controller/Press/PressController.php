<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Press;

use App\Application\Command\Press\CreatePressNote;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Press\ListPressNotes;
use App\Domain\DTO\User\User;
use App\Infrastructure\Nelmio\Operation\Press\CreatePressNoteOperation;
use App\Infrastructure\Nelmio\Operation\Press\ListPressNotesOperation;
use App\Infrastructure\Nelmio\Tag\PressTag;
use App\Infrastructure\Symfony\Http\DTO\Press\CreatePressNoteRequest;
use App\Infrastructure\Symfony\Security\User as SecurityUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[IsGranted('IS_AUTHENTICATED')]
    #[CreatePressNoteOperation]
    public function createPressNote(
        CommandBus $commandBus,
        #[CurrentUser]
        SecurityUser $user,
        #[MapRequestPayload]
        CreatePressNoteRequest $createPressNoteRequest,
        #[MapUploadedFile(constraints: [new Assert\Image(maxSize: '2M')])]
        UploadedFile $image,
    ): Response {
        $createPressNote = new CreatePressNote(
            user: new User($user->getId()),
            title: $createPressNoteRequest->title,
            subtitle: $createPressNoteRequest->subtitle,
            body: $createPressNoteRequest->body,
            featured: $createPressNoteRequest->featured,
            image: $image,
            link: $createPressNoteRequest->link,
        );

        $commandBus->dispatch($createPressNote);

        return new Response(null, Response::HTTP_CREATED);
    }
}
