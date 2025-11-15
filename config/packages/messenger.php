<?php

use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (FrameworkConfig $framework): void {
    $messenger = $framework->messenger();

    $messenger
        ->transport('sync')
        ->dsn('sync://');

    $messenger
        ->transport('async')
        ->dsn(env('MESSENGER_TRANSPORT_DSN'));

    $messenger
        ->routing(App\Application\Commons\Command\Command::class)
        ->senders(['sync']);

    $messenger
        ->routing(App\Application\Commons\Event\Event::class)
        ->senders(['async']);

    $messenger
        ->routing(App\Application\Commons\Query\Query::class)
        ->senders(['sync']);

    $messenger
        ->routing(\Symfony\Component\Mailer\Messenger\SendEmailMessage::class)
        ->senders(['async']);

    $messenger
        ->defaultBus('command.bus');

    $messenger
        ->bus('command.bus')
        ->middleware(App\Infrastructure\Symfony\Messenger\Middleware\ExceptionCatchMiddleware::class)
        ->middleware(App\Infrastructure\Symfony\Messenger\Middleware\RaiseEntityEventsMiddleware::class)
        ->middleware('validation')
        ->middleware('doctrine_transaction');

    $messenger
        ->bus('query.bus')
        ->middleware(App\Infrastructure\Symfony\Messenger\Middleware\ExceptionCatchMiddleware::class)
        ->middleware('validation');

    $messenger
        ->bus('event.bus')
        ->defaultMiddleware('allow_no_handlers')
        ->middleware(App\Infrastructure\Symfony\Messenger\Middleware\ExceptionCatchMiddleware::class)
        ->middleware(App\Infrastructure\Symfony\Messenger\Middleware\RaiseEntityEventsMiddleware::class)
        ->middleware('doctrine_transaction');
};
