<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function (ContainerConfigurator $container): void {

    $parameters = $container->parameters();
    $parameters
        ->set('app.registration.default_max_registrations_per_participant', 5)
        ->set(
            'app.registration.max_registrations_per_participant',
            env('MAX_REGISTRATIONS_PER_PARTICIPANT')
                ->default('app.registration.default_max_registrations_per_participant')
        );

    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services
        ->instanceof(\App\Application\Commons\Command\CommandHandler::class)
        ->tag('messenger.message_handler', ['bus' => 'command.bus']);

    $services
        ->instanceof(\App\Application\Commons\Event\EventHandler::class)
        ->tag('messenger.message_handler', ['bus' => 'event.bus']);

    $services
        ->instanceof(\App\Application\Commons\Query\QueryHandler::class)
        ->tag('messenger.message_handler', ['bus' => 'query.bus']);


    $services
        ->instanceof(\App\Infrastructure\Doctrine\Type\Type::class)
        ->tag('app.doctrine.type');

    $services
        ->load('App\\', '../src/')
        ->exclude([
            '../src/Kernel.php',
        ]);

    $services
        ->load('App\\Infrastructure\\Symfony\\Http\\Controller\\', '../src/Infrastructure/Symfony/Http/Controller')
        ->tag('controller.service_arguments');

    $services
        ->alias(
            \App\Application\Commons\Command\CommandBus::class,
            \App\Infrastructure\Symfony\Messenger\MessengerCommandBus::class
        )->public();

    $services
        ->alias(
            \App\Application\Commons\Query\QueryBus::class,
            \App\Infrastructure\Symfony\Messenger\MessengerQueryBus::class
        )->public();

    $services
        ->alias(
            \App\Application\Commons\Event\EventBus::class,
            \App\Infrastructure\Symfony\Messenger\MessengerEventBus::class
        )->public();

    $services
        ->set(\App\Application\Command\Registration\RegisterParticipantHandler::class)
        ->arg('$maxSegmentsPerParticipant', param('app.registration.max_registrations_per_participant'));
};