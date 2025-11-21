<?php

use Symfony\Config\DebugConfig;

return static function (DebugConfig $debug) {
    if ('dev' === $_SERVER['APP_ENV']) {
        $debug->dumpDestination('tcp://%env(VAR_DUMPER_SERVER)%');
    }
};
