<?php


use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (FrameworkConfig $framework): void {
    $mailer = $framework->mailer();

    $mailer
        ->dsn(env('MAILER_DSN'));
};
