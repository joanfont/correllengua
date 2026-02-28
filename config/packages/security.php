<?php

declare(strict_types=1);

use App\Infrastructure\Symfony\Security\User;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('security', [
        'password_hashers' => [
            User::class => [
                'id' => App\Infrastructure\Symfony\Security\SecurityPasswordHasher::class,
            ],
        ],
        'providers' => [
            'user_provider' => [
                'id' => App\Infrastructure\Symfony\Security\UserProvider::class,
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_profiler|_wdt|assets|build)/',
                'security' => false,
            ],
            'docs' => [
                'lazy' => true,
                'pattern' => '^/(doc\.json)?$',
                'stateless' => true,
                'http_basic' => [
                    'realm' => 'Correllengua API',
                ],
            ],
            'main' => [
                'lazy' => true,
                'provider' => 'user_provider',
                'stateless' => true,
                'http_basic' => [
                    'realm' => 'Correllengua API',
                ],
            ],
        ],
        'access_control' => [
            ['path' => '^/$', 'roles' => 'ROLE_USER'],
            ['path' => '^/doc\.json$', 'roles' => 'ROLE_USER'],
            ['path' => '^/admin', 'roles' => 'ROLE_USER'],
        ],
    ]);
};
