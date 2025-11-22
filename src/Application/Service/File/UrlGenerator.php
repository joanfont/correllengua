<?php

namespace App\Application\Service\File;

use App\Domain\Model\File\File;

interface UrlGenerator
{
    public function generate(File $file): string;
}
