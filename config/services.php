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
        ->set(\App\Infrastructure\Doctrine\Listener\EntityEventsListener::class)
        ->tag('doctrine.event_listener', ['event' => 'postPersist'])
        ->tag('doctrine.event_listener', ['event' => 'postUpdate'])
        ->tag('doctrine.event_listener', ['event' => 'postRemove']);

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

    $services
        ->set('app.filesystem.local', \App\Application\Service\File\Filesystem::class)
        ->factory([\App\Infrastructure\League\Service\File\LeagueFilesystemFactory::class, 'makeLocal'])
        ->arg('$root', param('kernel.project_dir'));


    $services
        ->alias('app.route.import_routes_from_file.filesystem', 'app.filesystem.local');

    $services
        ->alias('app.route.import_segments_from_file.filesystem', 'app.filesystem.local');

    $services
        ->set(\App\Application\Command\Route\ImportRoutesFromFileHandler::class)
        ->arg('$filesystem', service('app.route.import_routes_from_file.filesystem'));

    $services
        ->set(\App\Application\Command\Route\ImportSegmentsFromFileHandler::class)
        ->arg('$filesystem', service('app.route.import_segments_from_file.filesystem'));
};
