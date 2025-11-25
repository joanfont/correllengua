<?php

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $framework
        ->secret(env('APP_SECRET'))
        ->httpMethodOverride(false)
        ->handleAllThrowables(true);

    $framework
        ->session()
        ->handlerId(null)
        ->cookieSecure('auto')
        ->cookieSamesite('lax');

    $framework
        ->phpErrors()
        ->log(true);
};
