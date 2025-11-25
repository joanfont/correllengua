<?php

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $framework->test(true);
    $framework
        ->session()
        ->storageFactoryId('session.storage.factory.mock_file');
};
