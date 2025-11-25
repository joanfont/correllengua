<?php

declare(strict_types=1);

use Symfony\Config\NelmioApiDocConfig;

return static function (NelmioApiDocConfig $nelmioApiDoc): void {
    $nelmioApiDoc->documentation('info', [
        'title' => 'Correllengua API',
        'description' => 'API for managing route registrations and participants',
        'version' => '1.0.0',
    ]);

    $nelmioApiDoc->areas('default')
        ->pathPatterns([
            '^/!doc$',
            '^/route',
            '^/press',
            '^/registration',
        ]);

    // DTOs are automatically discovered through Model references in Operation classes
    // No manual configuration needed!
};

