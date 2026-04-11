<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('nelmio_api_doc', [
        'documentation' => [
            'info' => [
                'title' => 'Correllengua API',
                'description' => 'API for managing route registrations and participants',
                'version' => '1.0.0',
            ],
            'components' => [
                'securitySchemes' => [
                    'basicAuth' => [
                        'type' => 'http',
                        'scheme' => 'basic',
                    ],
                ],
            ],
        ],
        'areas' => [
            'default' => [
                'path_patterns' => [
                    '^/!doc$',
                    '^/auth',
                    '^/route',
                    '^/press',
                    '^/registration',
                    '^/admin',
                ],
            ],
        ],
    ]);
};
