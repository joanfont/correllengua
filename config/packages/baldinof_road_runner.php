<?php

use Symfony\Config\BaldinofRoadRunnerConfig;

return static function (BaldinofRoadRunnerConfig $baldinofRoadRunner): void {
    $baldinofRoadRunner->kernelReboot()
        ->strategy('on_exception')
        ->allowedExceptions([
            Symfony\Component\HttpKernel\Exception\HttpExceptionInterface::class,
        ]);

    $baldinofRoadRunner
        ->metrics()
        ->enabled(false);
};