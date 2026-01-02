<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'messenger' => [
            'transports' => [
                'sync' => [
                    'dsn' => 'sync://',
                ],
            ],
            'routing' => [
                App\Application\Commons\Command\Command::class => ['sync'],
                App\Application\Commons\Event\Event::class => ['sync'],
                App\Application\Commons\Query\Query::class => ['sync'],
            ],
            'default_bus' => 'command.bus',
            'buses' => [
                'command.bus' => [
                    'middleware' => [
                        App\Infrastructure\Symfony\Messenger\Middleware\ExceptionCatchMiddleware::class,
                        App\Infrastructure\Symfony\Messenger\Middleware\RaiseEntityEventsMiddleware::class,
                        'validation',
                        'doctrine_transaction',
                    ],
                ],
                'query.bus' => [
                    'middleware' => [
                        App\Infrastructure\Symfony\Messenger\Middleware\ExceptionCatchMiddleware::class,
                        'validation',
                    ],
                ],
                'event.bus' => [
                    'default_middleware' => 'allow_no_handlers',
                    'middleware' => [
                        App\Infrastructure\Symfony\Messenger\Middleware\ExceptionCatchMiddleware::class,
                        App\Infrastructure\Symfony\Messenger\Middleware\RaiseEntityEventsMiddleware::class,
                        'doctrine_transaction',
                    ],
                ],
            ],
        ],
    ]);
};
