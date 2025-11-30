<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes
        ->import('@FrameworkBundle/Resources/config/routing/errors.php')
        ->prefix('/_error');
};
