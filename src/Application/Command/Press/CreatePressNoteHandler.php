<?php

declare(strict_types=1);

namespace App\Application\Command\Press;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\File\FileUploader;
use App\Domain\Model\Press\PressNote;
use App\Domain\Model\Press\PressNoteId;
use App\Domain\Model\User\UserId;
use App\Domain\Repository\Press\PressNoteRepository;
use App\Domain\Repository\User\UserRepository;

readonly class CreatePressNoteHandler implements CommandHandler
{
    public function __construct(
        private PressNoteRepository $pressNoteRepository,
        private UserRepository $userRepository,
        private FileUploader $fileUploader,
    ) {
    }

    public function __invoke(CreatePressNote $createPressNote): void
    {
        $author = $this->userRepository->findById(UserId::from($createPressNote->user->id));
        $image = $this->fileUploader->upload($createPressNote->image);

        $pressNote = new PressNote(
            id: PressNoteId::generate(),
            author: $author,
            title: $createPressNote->title,
            subtitle: $createPressNote->subtitle,
            body: $createPressNote->body,
            image: $image,
            featured: $createPressNote->featured,
            link: $createPressNote->link,
        );

        $this->pressNoteRepository->add($pressNote);
    }
}
