<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'validation' => [
            'email_validation_mode' => 'html5',
        ],
    ]);
};
