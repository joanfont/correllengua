<?php

namespace App\Domain\Repository\File;

use App\Domain\Model\File\File;
use App\Domain\Model\File\FileId;

interface FileRepository
{
    public function add(File $file): void;

    public function findById(FileId $id): File;
}
