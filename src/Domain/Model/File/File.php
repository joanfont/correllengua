<?php

namespace App\Domain\Model\File;

use App\Domain\Model\Entity;

class File extends Entity
{
    private readonly string $id;

    public function __construct(
        FileId $id,
        private readonly string $name,
        private readonly string $path,
    ) {
        $this->id = (string) $id;
    }

    public function id(): FileId
    {
        return FileId::from($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function path(): string
    {
        return $this->path;
    }
}
