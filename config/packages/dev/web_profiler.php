<?php

use Symfony\Config\FrameworkConfig;
use Symfony\Config\WebProfilerConfig;

return static function (WebProfilerConfig $webProfiler, FrameworkConfig $framework): void {
    $webProfiler
        ->toolbar(true);

    $framework
        ->profiler()
        ->collectSerializerData(true);
};
