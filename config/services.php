<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services
        ->load('App\\', '../src/')
        ->exclude([
            '../src/Kernel.php',
        ]);

    $services
        ->load('App\\Application\\Controller\\', '../src/Application/Controller')
        ->tag('controller.service_arguments');

};