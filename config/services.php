<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();
    $parameters
        ->set('app.registration.default_max_registrations_per_participant', 5)
        ->set(
            'app.registration.max_registrations_per_participant',
            env('MAX_REGISTRATIONS_PER_PARTICIPANT')
                ->default('app.registration.default_max_registrations_per_participant'),
        )
        ->set('app.email.default_from', 'Correllengua <no-reply@correllengua.cat>')
        ->set('app.email.from', env('EMAIL_FROM')->default('app.email.default_from'))
        ->set('app.uploads.local.prefix', 'uploads')
        ->set('app.uploads.local.dir', param('kernel.project_dir').'/public/'.param('app.uploads.local.prefix'));

    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services
        ->instanceof(App\Application\Commons\Command\CommandHandler::class)
        ->tag('messenger.message_handler', ['bus' => 'command.bus']);

    $services
        ->instanceof(App\Application\Commons\Event\EventHandler::class)
        ->tag('messenger.message_handler', ['bus' => 'event.bus']);

    $services
        ->instanceof(App\Application\Commons\Query\QueryHandler::class)
        ->tag('messenger.message_handler', ['bus' => 'query.bus']);

    $services
        ->instanceof(App\Infrastructure\Doctrine\Type\Type::class)
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
        ->set(App\Infrastructure\Doctrine\Listener\EntityEventsListener::class)
        ->tag('doctrine.event_listener', ['event' => 'postPersist'])
        ->tag('doctrine.event_listener', ['event' => 'postUpdate'])
        ->tag('doctrine.event_listener', ['event' => 'postRemove']);

    $services
        ->alias(
            App\Application\Commons\Command\CommandBus::class,
            App\Infrastructure\Symfony\Messenger\MessengerCommandBus::class,
        )->public();

    $services
        ->alias(
            App\Application\Commons\Query\QueryBus::class,
            App\Infrastructure\Symfony\Messenger\MessengerQueryBus::class,
        )->public();

    $services
        ->alias(
            App\Application\Commons\Event\EventBus::class,
            App\Infrastructure\Symfony\Messenger\MessengerEventBus::class,
        )->public();

    $services
        ->set(App\Application\Command\Registration\RegisterParticipantHandler::class)
        ->arg('$maxSegmentsPerParticipant', param('app.registration.max_registrations_per_participant'));

    $services
        ->set('app.filesystem.project', App\Application\Service\File\Filesystem::class)
        ->factory([App\Infrastructure\League\Service\File\LeagueFilesystemFactory::class, 'makeLocal'])
        ->arg('$root', param('kernel.project_dir'));

    $services
        ->set('app.filesystem.uploads', App\Application\Service\File\Filesystem::class)
        ->factory([App\Infrastructure\League\Service\File\LeagueFilesystemFactory::class, 'makeLocal'])
        ->arg('$root', param('app.uploads.local.dir'));

    $services
        ->set('app.filesystem.root', App\Application\Service\File\Filesystem::class)
        ->factory([App\Infrastructure\League\Service\File\LeagueFilesystemFactory::class, 'makeLocal'])
        ->arg('$root', '/');

    $services
        ->alias('app.route.import_routes_from_file.filesystem', 'app.filesystem.project');

    $services
        ->alias('app.route.import_segments_from_file.filesystem', 'app.filesystem.project');

    $services
        ->alias('app.registration.registration_created.send_email.filesystem', 'app.filesystem.project');

    $services
        ->set(App\Application\Command\Route\ImportRoutesFromFileHandler::class)
        ->arg('$filesystem', service('app.route.import_routes_from_file.filesystem'));

    $services
        ->set(App\Application\Command\Route\ImportItinerariesFromFileHandler::class)
        ->arg('$filesystem', service('app.route.import_segments_from_file.filesystem'));

    $services
        ->set(App\Application\Command\Route\ImportSegmentsFromFileHandler::class)
        ->arg('$filesystem', service('app.route.import_segments_from_file.filesystem'));

    $services
        ->set(App\Infrastructure\Symfony\Mailer\Notification\EmailRegistrationCreatedNotification::class)
        ->arg('$from', param('app.email.from'))
        ->arg('$filesystem', service('app.registration.registration_created.send_email.filesystem'))
        ->arg('$templatePath', '/templates/registrationCreated.html.twig');

    $services
        ->set(App\Infrastructure\Symfony\Http\File\SymfonyFileUploader::class)
        ->arg('$rootFilesystem', service('app.filesystem.root'))
        ->arg('$uploadsFilesystem', service('app.filesystem.uploads'));

    $services
        ->set(App\Infrastructure\Symfony\Http\File\SymfonyLocalUrlGenerator::class)
        ->arg('$prefix', param('app.uploads.local.prefix'));

    $services
        ->set(App\Infrastructure\Symfony\Validator\Constraints\MaxSegmentsCountValidator::class)
        ->arg('$maxSegmentsPerParticipant', param('app.registration.max_registrations_per_participant'));

    $services
        ->set(App\Infrastructure\System\Service\Auth\Sha256PasswordHasher::class)
        ->arg('$salt', param('kernel.secret'));
};
