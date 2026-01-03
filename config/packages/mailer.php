<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'mailer' => [
            'dsn' => env('MAILER_DSN'),
        ],
    ]);
};
