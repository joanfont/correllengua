<?php

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

use Symfony\Config\DoctrineConfig;
use Symfony\Config\FrameworkConfig;

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
        ->enableNativeLazyObjects(true);

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
};
