<?php

use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog) {
    $monolog
        ->handler('main')
        ->type('fingers_crossed')
        ->actionLevel('error')
        ->handler('nested')
        ->excludedHttpCode(404)
        ->excludedHttpCode(405)
        ->bufferSize(50);

    $monolog
        ->handler('nested')
        ->type('stream')
        ->path('php://stderr')
        ->level('debug')
        ->formatter('monolog.formatter.json');

    $monolog
        ->handler('console')
        ->type('console')
        ->processPsr3Messages(false);

    $monolog
        ->channels(['deprecation'])
        ->handler('deprecation')
        ->type('stream')
        ->path('php://stderr')
        ->formatter('monolog.formatter.json');
};
