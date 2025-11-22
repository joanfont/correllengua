<?php

namespace App\Infrastructure\Symfony\Http\File;

use App\Application\Service\File\UrlGenerator;
use App\Domain\Model\File\File;
use Symfony\Component\HttpFoundation\RequestStack;

class SymfonyLocalUrlGenerator implements UrlGenerator
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly string $prefix,
    ) {
    }

    public function generate(File $file): string
    {
        return implode('/', [
            $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost(),
            $this->prefix,
            $file->path(),
        ]);
    }
}
