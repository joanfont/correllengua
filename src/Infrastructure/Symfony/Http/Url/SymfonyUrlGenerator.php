<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Url;

use App\Application\Service\Url\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SymfonyUrlGenerator implements UrlGenerator
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function generate(string $name, array $parameters = []): string
    {
        return $this->urlGenerator->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
