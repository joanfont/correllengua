<?php

use Symfony\Config\DoctrineConfig;
use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (DoctrineConfig $doctrine, FrameworkConfig $framework): void {
    $doctrine
        ->dbal()
        ->connection('default')
        ->url(env('resolve:DATABASE_URL'))
        ->profilingCollectBacktrace(param('kernel.debug'))
        ->useSavepoints(true);

    $doctrine
        ->orm()
        ->autoGenerateProxyClasses(true)
        ->enableLazyGhostObjects(true);

    $doctrine
        ->orm()
        ->entityManager('default')
        ->reportFieldsWhereDeclared(true)
        ->namingStrategy('doctrine.orm.naming_strategy.underscore_number_aware')
        ->mapping('App')
        ->type('xml')
        ->isBundle(false)
        ->dir('%kernel.project_dir%/src/Infrastructure/Doctrine/Config/ORM')
        ->prefix('App\Domain\Model');

    if ('test' === $_ENV['APP_ENV']) {
        $doctrine
            ->dbal()
            ->connection('default')
            ->dbnameSuffix('_test%env(default::TEST_TOKEN)%');
    }

    if ('prod' === $_ENV['APP_ENV']) {
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
    }
};
