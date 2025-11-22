<?php

namespace App\Application\Command\Press;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\File\FileUploader;
use App\Domain\Model\Press\PressNote;
use App\Domain\Model\Press\PressNoteId;
use App\Domain\Repository\Press\PressNoteRepository;

readonly class CreatePressNoteHandler implements CommandHandler
{
    public function __construct(
        private FileUploader $fileUploader,
        private PressNoteRepository $pressNoteRepository,
    ) {
    }

    public function __invoke(CreatePressNote $createPressNote): void
    {
        $image = $this->fileUploader->upload($createPressNote->image);

        $pressNote = new PressNote(
            id: PressNoteId::generate(),
            title: $createPressNote->title,
            subtitle: $createPressNote->subtitle,
            body: $createPressNote->body,
            image: $image,
            featured: $createPressNote->featured,
        );

        $this->pressNoteRepository->add($pressNote);
    }
}
