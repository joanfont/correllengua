<?php

namespace App\Domain\Exception\File;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\File\FileId;

final class FileNotFoundException extends NotFoundException
{
    public static function fromId(FileId $id): self
    {
        return new self(sprintf('File with id = %s not found.', $id));
    }
}