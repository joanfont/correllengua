<?php

use Symfony\Config\TwigConfig;

return static function (TwigConfig $twig): void {
    $twig->fileNamePattern('*.twig');

    if ('test' === $_ENV['APP_ENV']) {
        $twig->strictVariables(true);
    }
};