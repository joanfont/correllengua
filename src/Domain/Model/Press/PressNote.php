<?php

declare(strict_types=1);

namespace App\Domain\Model\Press;

use App\Domain\Model\Entity;
use App\Domain\Model\File\File;

class PressNote extends Entity
{
    private readonly string $id;

    public function __construct(
        PressNoteId $id,
        private readonly string $title,
        private readonly string $subtitle,
        private readonly string $body,
        private readonly File $image,
        private readonly bool $featured,
        private readonly ?string $link = null,
    ) {
        $this->id = (string) $id;
    }

    public function id(): PressNoteId
    {
        return PressNoteId::from($this->id);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function subtitle(): string
    {
        return $this->subtitle;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function image(): File
    {
        return $this->image;
    }

    public function featured(): bool
    {
        return $this->featured;
    }

    public function link(): ?string
    {
        return $this->link;
    }
}
