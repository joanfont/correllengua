<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $container): void {
    $container->extension('nelmio_cors', [
        'defaults' => [
            'origin_regex' => false,
            'allow_origin' => [env('CORS_ALLOW_ORIGIN')],
            'allow_methods' => ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE'],
            'allow_headers' => ['Content-Type', 'Authorization', 'Accept'],
            'expose_headers' => ['Link'],
            'max_age' => 3600,
        ],
        'paths' => [
            '^/' => null,
        ],
    ]);
};
