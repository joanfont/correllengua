<?php

use Symfony\Config\DoctrineConfig;
use Symfony\Config\FrameworkConfig;


return static function (DoctrineConfig $doctrine, FrameworkConfig $framework): void {
    $doctrine
        ->dbal()
        ->connection('default')
        ->dbnameSuffix('_test%env(default::TEST_TOKEN)%');

};
