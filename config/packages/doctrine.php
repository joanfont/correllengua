<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    $container->extension('doctrine', [
        'dbal' => [
            'connections' => [
                'default' => [
                    'url' => env('resolve:DATABASE_URL'),
                    'profiling_collect_backtrace' => param('kernel.debug'),
                    'use_savepoints' => true,
                ],
            ],
        ],
        'orm' => [
            'auto_generate_proxy_classes' => true,
            'enable_native_lazy_objects' => false,
            'controller_resolver' => [
                'auto_mapping' => false,
            ],
            'entity_managers' => [
                'default' => [
                    'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
                    'mappings' => [
                        'App' => [
                            'type' => 'xml',
                            'is_bundle' => false,
                            'dir' => '%kernel.project_dir%/src/Infrastructure/Doctrine/Config/ORM',
                            'prefix' => 'App\Domain\Model',
                        ],
                    ],
                ],
            ],
        ],
    ]);
};
