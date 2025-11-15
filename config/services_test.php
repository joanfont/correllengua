<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $container): void {

    $container->services()
        ->defaults()
        ->public();
};
