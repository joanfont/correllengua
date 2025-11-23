<?php

namespace App\Tests\Unit\Infrastructure\Symfony\Twig;

use App\Infrastructure\Symfony\Twig\TwigTemplateRenderer;
use App\Tests\TestCase;

class TwigTemplateRendererTest extends TestCase
{
    public function testRenderReplacesVariablesAndReturnsString(): void
    {
        $renderer = new TwigTemplateRenderer();

        $template = 'Hello {{ name }}!';

        $output = $renderer->render($template, ['name' => 'John']);

        static::assertSame('Hello John!', $output);
    }

    public function testRenderWithEmptyContext(): void
    {
        $renderer = new TwigTemplateRenderer();

        $template = 'Static content';

        $output = $renderer->render($template);

        static::assertSame('Static content', $output);
    }
}

