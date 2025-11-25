<?php

namespace App\Application\Service\File;

use App\Domain\Model\File\File;
use SplFileInfo;

interface FileUploader
{
    public function upload(SplFileInfo $file): File;
}
