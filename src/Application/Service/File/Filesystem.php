<?php

namespace App\Application\Service\File;

interface Filesystem
{
    public function read(string $path): string;
}
