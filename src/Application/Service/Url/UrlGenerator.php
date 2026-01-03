<?php

declare(strict_types=1);

namespace App\Application\Service\Url;

interface UrlGenerator
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function generate(string $name, array $parameters = []): string;
}
