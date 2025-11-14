<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Doctrine\Type\Type;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services
        ->instanceof(\App\Infrastructure\Doctrine\Type\Type::class)
        ->tag('app.doctrine.type');

    $services
        ->load('App\\', '../src/')
        ->exclude([
            '../src/Kernel.php',
        ]);

    $services
        ->load('App\\Application\\Controller\\', '../src/Application/Controller')
        ->tag('controller.service_arguments');
};