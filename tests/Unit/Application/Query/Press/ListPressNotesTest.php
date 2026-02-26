<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query\Press;

use App\Application\Query\Press\ListPressNotes;
use App\Domain\DTO\File\File;
use App\Domain\DTO\Press\PressNote;
use App\Domain\Provider\Press\PressNoteProvider;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ListPressNotesTest extends TestCase
{
    private readonly PressNoteProvider&MockObject $pressNoteProvider;

    protected function setUp(): void
    {
        $this->pressNoteProvider = $this->createMock(PressNoteProvider::class);

        self::set(PressNoteProvider::class, $this->pressNoteProvider);
    }

    public function testReturnsListOfPressNotes(): void
    {
        $file = new File(
            url: 'https://example.com/uploads/2026/image.jpg',
        );

        $pressNotes = [
            new PressNote(
                id: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
                title: 'First Press Note',
                subtitle: 'First Subtitle',
                body: 'First Body',
                featured: true,
                image: $file,
                link: 'https://example.com/first',
                createdAt: '2026-02-26T10:00:00+00:00',
            ),
            new PressNote(
                id: 'b2c3d4e5-f6a7-8901-bcde-f12345678901',
                title: 'Second Press Note',
                subtitle: 'Second Subtitle',
                body: 'Second Body',
                featured: false,
                image: $file,
                link: null,
                createdAt: '2026-02-25T09:00:00+00:00',
            ),
        ];

        $this->pressNoteProvider
            ->expects($this->once())
            ->method('listAll')
            ->willReturn($pressNotes);

        $query = new ListPressNotes();

        $result = self::handleQuery($query);

        self::assertCount(2, $result);
        self::assertSame('First Press Note', $result[0]->title);
        self::assertSame('Second Press Note', $result[1]->title);
    }

    public function testReturnsEmptyArrayWhenNoPressNotes(): void
    {
        $this->pressNoteProvider
            ->expects($this->once())
            ->method('listAll')
            ->willReturn([]);

        $query = new ListPressNotes();

        $result = self::handleQuery($query);

        self::assertEmpty($result);
    }
}
