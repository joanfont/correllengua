<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'messenger' => [
            'transports' => [
                'sync' => [
                    'dsn' => 'sync://',
                ],
                'async' => [
                    'dsn' => env('MESSENGER_TRANSPORT_DSN'),
                ],
            ],
            'routing' => [
                App\Application\Commons\Command\Command::class => ['sync'],
                App\Application\Commons\Event\Event::class => ['async'],
                App\Application\Commons\Query\Query::class => ['sync'],
                Symfony\Component\Mailer\Messenger\SendEmailMessage::class => ['async'],
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
