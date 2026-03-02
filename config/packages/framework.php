<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'secret' => env('APP_SECRET'),
        'http_method_override' => false,
        'handle_all_throwables' => true,
        'session' => [
            'handler_id' => null,
            'cookie_secure' => 'auto',
            'cookie_samesite' => 'lax',
        ],
        'php_errors' => [
            'log' => true,
        ],
        'trusted_headers' => [
            'x-forwarded-for',
            'x-forwarded-proto',
            'x-forwarded-host',
            'x-forwarded-port',
            'x-forwarded-prefix',
        ],
    ]);
};
