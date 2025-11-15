<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;


return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services
        ->set(\App\Application\Command\Registration\RegisterParticipantHandler::class)
        ->arg('$maxSegmentsPerParticipant', param('app.registration.max_participants_per_segment'));
};