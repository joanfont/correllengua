<?php

use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog) {
    $monolog
        ->channels(['deprecation']);
};
