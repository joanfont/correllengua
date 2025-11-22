<?php


use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (FrameworkConfig $framework): void {
    $framework
        ->mailer()
        ->dsn(env('MAILER_DSN'));
};
