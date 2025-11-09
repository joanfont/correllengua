<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $framework
        ->router()
        ->utf8(true);

    if ('prod' === $_ENV['APP_ENV']) {
        $framework
            ->router()
            ->strictRequirements(null);
    }
};
