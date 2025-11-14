<?php

use Symfony\Config\FrameworkConfig;
use Symfony\Config\WebProfilerConfig;

return static function (WebProfilerConfig $webProfiler, FrameworkConfig $framework): void {
    if ('dev' === $_ENV['APP_ENV']) {
        $webProfiler
            ->toolbar(true);

        $framework
            ->profiler()
            ->collectSerializerData(true);
    }

    if ('test' === $_ENV['APP_ENV']) {
        $framework->profiler()
            ->collect(false);
    }
};
