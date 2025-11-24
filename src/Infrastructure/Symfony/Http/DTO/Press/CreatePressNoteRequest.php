<?php

namespace App\Infrastructure\Symfony\Http\DTO\Press;

readonly class CreatePressNoteRequest
{
    public function __construct(
        public string $title,
        public string $subtitle,
        public string $body,
        public bool $featured,
        public \SplFileInfo $image,
    ) {}
}
