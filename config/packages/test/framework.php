<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'test' => true,
        'session' => [
            'storage_factory_id' => 'session.storage.factory.mock_file',
        ],
    ]);
};
