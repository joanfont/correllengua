<?php

namespace App\Domain\Model\Press;

use App\Domain\Model\Entity;
use App\Domain\Model\File\File;

class PressNote extends Entity
{
    private string $id;

    public function __construct(
        PressNoteId $id,
        private string $title,
        private string $subtitle,
        private string $body,
        private File $image,
        private bool $featured,
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
}