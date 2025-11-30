<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('nelmio_api_doc', [
        'documentation' => [
            'info' => [
                'title' => 'Correllengua API',
                'description' => 'API for managing route registrations and participants',
                'version' => '1.0.0',
            ],
        ],
        'areas' => [
            'default' => [
                'path_patterns' => [
                    '^/!doc$',
                    '^/route',
                    '^/press',
                    '^/registration',
                ],
            ],
        ],
    ]);
};
