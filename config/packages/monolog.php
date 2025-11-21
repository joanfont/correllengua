<?php

use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog) {

    $monolog->channels(['deprecation']);

    // when@dev
    if ('dev' === $_ENV['APP_ENV']) {
        $handlerMain = $monolog->handler('main');
        $handlerMain
            ->type('stream')
            ->path('%kernel.logs_dir%/%kernel.environment%.log')
            ->level('debug')
            ->channels(['!event']);

        $handlerConsole = $monolog->handler('console');
        $handlerConsole
            ->type('console')
            ->processPsr3Messages(false)
            ->channels(['!event', '!doctrine', '!console']);
    }

    // when@test
    if ('test' === $_ENV['APP_ENV']) {
        $handlerMain = $monolog->handler('main');
        $handlerMain
            ->type('fingers_crossed')
            ->actionLevel('error')
            ->handler('nested')
            ->excludedHttpCode(404)
            ->excludedHttpCode(405)
            ->channels(['!event']);

        $handlerNested = $monolog->handler('nested');
        $handlerNested
            ->type('stream')
            ->path('%kernel.logs_dir%/%kernel.environment%.log')
            ->level('debug');
    }

    // when@prod
    if ('prod' === $_ENV['APP_ENV']) {
        $handlerMain = $monolog->handler('main');
        $handlerMain
            ->type('fingers_crossed')
            ->actionLevel('error')
            ->handler('nested')
            ->excludedHttpCode(404)
            ->excludedHttpCode(405)
            ->channels(['!deprecation'])
            ->bufferSize(50);

        $handlerNested = $monolog->handler('nested');
        $handlerNested
            ->type('stream')
            ->path('php://stderr')
            ->level('debug')
            ->formatter('monolog.formatter.json');

        $handlerConsole = $monolog->handler('console');
        $handlerConsole
            ->type('console')
            ->processPsr3Messages(false)
            ->channels(['!event', '!doctrine']);

        $handlerDeprecation = $monolog->handler('deprecation');
        $handlerDeprecation
            ->type('stream')
            ->channels(['deprecation'])
            ->path('php://stderr')
            ->formatter('monolog.formatter.json');
    }
};
