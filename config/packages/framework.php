<?php


use Symfony\Config\FrameworkConfig;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

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
