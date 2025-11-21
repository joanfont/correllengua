<?php

namespace App\Domain\Model\File;

use App\Domain\Model\Entity;

class File extends Entity
{
    private string $id;

    public function __construct(
        FileId $id,
        private string $name,
        private string $path,
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