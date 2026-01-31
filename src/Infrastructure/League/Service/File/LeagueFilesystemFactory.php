<?php

declare(strict_types=1);

namespace App\Infrastructure\League\Service\File;

use App\Application\Service\File\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;

class LeagueFilesystemFactory
{
    /**
     * @param array<string, mixed> $config
     */
    public static function makeLocal(string $root, array $config = []): Filesystem
    {
        $adapter = new LocalFilesystemAdapter(
            location: $root,
            visibility: PortableVisibilityConverter::fromArray([
                'file' => [
                    'public' => 0o755,
                    'private' => 0o600,
                ],
                'dir' => [
                    'public' => 0o755,
                    'private' => 0o700,
                ],
            ]),
            lazyRootCreation: true
        );

        return new LeagueFilesystem($adapter, $config);
    }
}
