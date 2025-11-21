<?php

namespace App\Application\Service\Template;

interface TemplateRenderer
{
    public function render(string $templateContents, array $context = []): string;
}
