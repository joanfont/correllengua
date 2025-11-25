<?php

namespace App\Tests\Unit\Application\Command\Press;

use App\Application\Command\Press\CreatePressNote;
use App\Application\Service\File\FileUploader;
use App\Domain\Model\File\File;
use App\Domain\Model\Press\PressNote;
use App\Domain\Repository\Press\PressNoteRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreatePressNoteTest extends TestCase
{
    private readonly FileUploader&MockObject $fileUploader;
    private readonly PressNoteRepository&MockObject $repository;

    protected function setUp(): void
    {
        $this->fileUploader = $this->createMock(FileUploader::class);
        $this->repository = $this->createMock(PressNoteRepository::class);

        self::set(FileUploader::class, $this->fileUploader);
        self::set(PressNoteRepository::class, $this->repository);
    }

    public function testCreatesPressNoteAndAddsToRepository(): void
    {
        $title = 'Important announcement';
        $subtitle = 'Short subtitle';
        $body = 'Details of the press note.';
        $image = self::asset('image.png');
        $file = $this->createMock(File::class);
        $featured = true;

        $this->fileUploader
            ->expects($this->once())
            ->method('upload')
            ->with($image)
            ->willReturn($file);

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(fn (PressNote $pressNote): bool => $pressNote->title() === $title
                && $pressNote->subtitle() === $subtitle
                && $pressNote->body() === $body
                && $pressNote->image() === $file
                && $pressNote->featured() === $featured));

        $createPressNote = new CreatePressNote(
            title: $title,
            subtitle: $subtitle,
            body: $body,
            featured: $featured,
            image: $image,
        );

        self::handleCommand($createPressNote);
    }
}
