<?php

namespace App\Infrastructure\Doctrine\Provider\File;

use App\Application\Service\File\UrlGenerator;
use App\Domain\DTO\File\File;
use App\Domain\Model\File\File as FileEntity;

class FileFactory
{
    public function __construct(private readonly UrlGenerator $urlGenerator)
    {
    }

    public function fromEntity(FileEntity $file): File
    {
        return new File(
            url: $this->urlGenerator->generate($file)
        );
    }
}