<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('debug', [
        'dump_destination' => 'tcp://%env(VAR_DUMPER_SERVER)%',
    ]);
};
