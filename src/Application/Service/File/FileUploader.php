<?php

namespace App\Application\Service\File;

use App\Domain\Model\File\File;

interface FileUploader
{
    public function upload(\SplFileInfo $file): File;
}