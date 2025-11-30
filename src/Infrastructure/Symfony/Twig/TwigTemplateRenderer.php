<?php

namespace App\Infrastructure\Symfony\Twig;

use App\Application\Service\Template\TemplateRenderer;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigTemplateRenderer implements TemplateRenderer
{
    private readonly Environment $twig;

    public function __construct()
    {
        $this->twig = new Environment(new ArrayLoader());
    }

    public function render(string $templateContents, array $context = []): string
    {
        $template = $this->twig->createTemplate($templateContents);

        return $template->render($context);
    }
}
