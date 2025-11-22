<?php

namespace App\Application\Service\File;

interface Filesystem
{
    public function read(string $path): string;

    public function write(string $path, string $contents): void;

    public function delete(string $path): void;
}
