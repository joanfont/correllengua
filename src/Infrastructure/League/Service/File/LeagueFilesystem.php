<?php

declare(strict_types=1);

namespace App\Infrastructure\League\Service\File;

use App\Application\Service\File\Filesystem;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemAdapter;

class LeagueFilesystem implements Filesystem
{
    private readonly Flysystem $flysystem;

    public function __construct(private readonly FilesystemAdapter $adapter)
    {
        $this->flysystem = new Flysystem($this->adapter);
    }

    public function read(string $path): string
    {
        return $this->flysystem->read($path);
    }

    public function write(string $path, string $contents): void
    {
        $this->flysystem->write($path, $contents);
    }

    public function delete(string $path): void
    {
        $this->flysystem->delete($path);
    }
}
