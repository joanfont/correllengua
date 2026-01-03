<?php

declare(strict_types=1);

namespace App\Application\Service\Template;

interface TemplateRenderer
{
    /**
     * @param array<string, mixed> $context
     */
    public function render(string $templateContents, array $context = []): string;
}
