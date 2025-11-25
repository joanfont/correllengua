<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('app.swagger', '/doc.json')
        ->methods(['GET'])
        ->defaults(['_controller' => 'nelmio_api_doc.controller.swagger']);

    $routes->add('app.swagger_ui', '/doc')
        ->methods(['GET'])
        ->defaults(['_controller' => 'nelmio_api_doc.controller.swagger_ui']);
};

