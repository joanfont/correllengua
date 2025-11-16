<?php

namespace App\Infrastructure\League\Service\File;

use App\Application\Service\File\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class LeagueFilesystemFactory
{
    public static function makeLocal(string $root): Filesystem
    {
        $adapter = new LocalFilesystemAdapter($root);

        return new LeagueFilesystem($adapter);
    }
}