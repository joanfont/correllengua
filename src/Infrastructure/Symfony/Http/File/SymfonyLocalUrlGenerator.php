<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\File;

use App\Application\Service\File\UrlGenerator;
use App\Domain\Model\File\File;

use function implode;

use RuntimeException;
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
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof \Symfony\Component\HttpFoundation\Request) {
            throw new RuntimeException('No current request available');
        }

        return implode('/', [
            $request->getSchemeAndHttpHost(),
            $this->prefix,
            $file->path(),
        ]);
    }
}
