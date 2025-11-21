<?php

use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog) {

    $monolog->channels(['deprecation']);

    if ('dev' === $_ENV['APP_ENV']) {
        $monolog
            ->handler('main')
            ->type('stream')
            ->path('%kernel.logs_dir%/%kernel.environment%.log')
            ->level('debug')
            ->channels([]); // listen to all channels

        $monolog
            ->handler('console')
            ->type('console')
            ->processPsr3Messages(false)
            ->channels([]); // listen to all channels
    }

    if ('test' === $_ENV['APP_ENV']) {
        $monolog
            ->handler('main')
            ->type('fingers_crossed')
            ->actionLevel('error')
            ->handler('nested')
            ->excludedHttpCode(404)
            ->excludedHttpCode(405)
            ->channels([]); // all channels

        $monolog
            ->handler('nested')
            ->type('stream')
            ->path('%kernel.logs_dir%/%kernel.environment%.log')
            ->level('debug');
    }

    if ('prod' === $_ENV['APP_ENV']) {
        $monolog
            ->handler('main')
            ->type('fingers_crossed')
            ->actionLevel('error')
            ->handler('nested')
            ->excludedHttpCode(404)
            ->excludedHttpCode(405)
            ->bufferSize(50)
            ->channels([]); // all channels

        $monolog
            ->handler('nested')
            ->type('stream')
            ->path('php://stderr')
            ->level('debug')
            ->formatter('monolog.formatter.json');

        $monolog
            ->handler('console')
            ->type('console')
            ->processPsr3Messages(false)
            ->channels([]); // all channels

        $monolog
            ->handler('deprecation')
            ->type('stream')
            ->channels(['deprecation']) // specific channel
            ->path('php://stderr')
            ->formatter('monolog.formatter.json');
    }
};
