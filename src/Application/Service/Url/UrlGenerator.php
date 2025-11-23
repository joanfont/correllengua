<?php

namespace App\Application\Service\Url;

interface UrlGenerator
{
    public function generate(string $name, array $parameters = []): string;
}
