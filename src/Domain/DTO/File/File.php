<?php

declare(strict_types=1);

namespace App\Domain\DTO\File;

readonly class File
{
    public function __construct(
        public string $url,
    ) {
    }
}
