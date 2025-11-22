<?php

use Symfony\Config\DoctrineConfig;
use Symfony\Config\FrameworkConfig;


return static function (DoctrineConfig $doctrine, FrameworkConfig $framework): void {
    $doctrine
        ->orm()
        ->autoGenerateProxyClasses(false)
        ->proxyDir('%kernel.build_dir%/doctrine/orm/Proxies');

    $doctrine
        ->orm()
        ->entityManager('default')
        ->queryCacheDriver()
        ->type('pool')
        ->pool('doctrine.system_cache_pool')
        ->resultCacheDriver()
        ->type('pool')
        ->pool('doctrine.result_cache_pool');

    $framework
        ->cache()
        ->pool('doctrine.result_cache_pool')
        ->adapters('cache.app')
        ->pool('doctrine.system_cache_pool')
        ->adapters('cache.system');
};
