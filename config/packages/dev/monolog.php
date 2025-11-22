<?php

use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog) {
    $monolog
        ->handler('main')
        ->type('stream')
        ->path('%kernel.logs_dir%/%kernel.environment%.log')
        ->level('debug')
        ->channels([]);

    $monolog
        ->handler('console')
        ->type('console')
        ->processPsr3Messages(false)
        ->channels([]);
};
