<?php

use Symfony\Config\DebugConfig;

return static function (DebugConfig $debug) {
    $debug->dumpDestination('tcp://%env(VAR_DUMPER_SERVER)%');
};
