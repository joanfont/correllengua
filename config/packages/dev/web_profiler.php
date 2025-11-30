<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('web_profiler', [
        'toolbar' => true,
    ]);

    $container->extension('framework', [
        'profiler' => [
            'collect_serializer_data' => true,
        ],
    ]);
};
