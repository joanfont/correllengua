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
        ->channels([]);

    $monolog
        ->handler('nested')
        ->type('stream')
        ->path('%kernel.logs_dir%/%kernel.environment%.log')
        ->level('debug');
};
