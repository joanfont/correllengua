<?php

declare(strict_types=1);

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (Symfony\Config\FrameworkConfig $framework): void {
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

    if ('test' === $_ENV['APP_ENV']) {
        $framework->test(true);
        $framework
            ->session()
            ->storageFactoryId('session.storage.factory.mock_file');
    }
};
