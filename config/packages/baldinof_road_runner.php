<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('baldinof_road_runner', [
        'kernel_reboot' => [
            'strategy' => 'on_exception',
            'allowed_exceptions' => [
                Symfony\Component\HttpKernel\Exception\HttpExceptionInterface::class,
            ],
        ],
        'metrics' => [
            'enabled' => false,
        ],
    ]);
};
