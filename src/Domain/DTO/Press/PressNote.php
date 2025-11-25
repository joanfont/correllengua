<?php

namespace App\Domain\DTO\Press;

use App\Domain\DTO\File\File;

readonly class PressNote
{
    public function __construct(
        public string $id,
        public string $title,
        public string $subtitle,
        public string $body,
        public bool $featured,
        public File $image,
    ) {
    }
}
