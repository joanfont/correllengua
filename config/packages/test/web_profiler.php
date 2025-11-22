<?php

use Symfony\Config\FrameworkConfig;
use Symfony\Config\WebProfilerConfig;

return static function (WebProfilerConfig $webProfiler, FrameworkConfig $framework): void {
    $framework->profiler()
        ->collect(false);
};
