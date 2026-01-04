<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Press;

use App\Application\Command\Press\CreatePressNote;
use App\Application\Service\File\FileUploader;
use App\Domain\DTO\User\User as UserDTO;
use App\Domain\Model\File\File;
use App\Domain\Model\Press\PressNote;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Repository\Press\PressNoteRepository;
use App\Domain\Repository\User\UserRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreatePressNoteTest extends TestCase
{
    private readonly FileUploader&MockObject $fileUploader;

    private readonly PressNoteRepository&MockObject $repository;

    private readonly UserRepository&MockObject $userRepository;

    protected function setUp(): void
    {
        $this->fileUploader = $this->createMock(FileUploader::class);
        $this->repository = $this->createMock(PressNoteRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        self::set(FileUploader::class, $this->fileUploader);
        self::set(PressNoteRepository::class, $this->repository);
        self::set(UserRepository::class, $this->userRepository);
    }

    public function testCreatesPressNoteAndAddsToRepository(): void
    {
        $userId = 'a3c7e2d5-8f4b-4c1e-b9d0-6a1e9f3c8b7a';
        $userDTO = new UserDTO($userId);
        $author = $this->createStub(User::class);

        $title = 'Important announcement';
        $subtitle = 'Short subtitle';
        $body = 'Details of the press note.';
        $image = self::asset('image.png');
        $file = $this->createStub(File::class);
        $link = 'https://example.com/press-note';
        $featured = true;

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with($this->callback(fn (UserId $id): bool => (string) $id === $userId))
            ->willReturn($author);

        $this->fileUploader
            ->expects($this->once())
            ->method('upload')
            ->with($image)
            ->willReturn($file);

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(fn (PressNote $pressNote): bool => $pressNote->author() === $author
                && $pressNote->title() === $title
                && $pressNote->subtitle() === $subtitle
                && $pressNote->body() === $body
                && $pressNote->image() === $file
                && $pressNote->featured() === $featured
                && $pressNote->link() === $link));

        $createPressNote = new CreatePressNote(
            user: $userDTO,
            title: $title,
            subtitle: $subtitle,
            body: $body,
            featured: $featured,
            image: $image,
            link: $link,
        );

        self::handleCommand($createPressNote);
    }

    public function testCreatesPressNoteWithoutLink(): void
    {
        $userId = 'b4d8f3e6-9a5c-5d2f-c0e1-7b2f0a4d9c8b';
        $userDTO = new UserDTO($userId);
        $author = $this->createStub(User::class);

        $title = 'Another announcement';
        $subtitle = 'Different subtitle';
        $body = 'More details here.';
        $image = self::asset('image.png');
        $file = $this->createStub(File::class);
        $featured = false;

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with($this->callback(fn (UserId $id): bool => (string) $id === $userId))
            ->willReturn($author);

        $this->fileUploader
            ->expects($this->once())
            ->method('upload')
            ->with($image)
            ->willReturn($file);

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(fn (PressNote $pressNote): bool => $pressNote->author() === $author
                && $pressNote->title() === $title
                && $pressNote->subtitle() === $subtitle
                && $pressNote->body() === $body
                && $pressNote->image() === $file
                && $pressNote->featured() === $featured
                && null === $pressNote->link()));

        $createPressNote = new CreatePressNote(
            user: $userDTO,
            title: $title,
            subtitle: $subtitle,
            body: $body,
            featured: $featured,
            image: $image,
        );

        self::handleCommand($createPressNote);
    }
}
