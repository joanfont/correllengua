<?php

use Symfony\Config\DoctrineMigrationsConfig;

return static function (DoctrineMigrationsConfig $doctrineMigrations): void {
    $doctrineMigrations
        ->enableProfiler(false);

    $doctrineMigrations
        ->migrationsPath('DoctrineMigrations', '%kernel.project_dir%/migrations');

    $doctrineMigrations
        ->storage()
        ->tableStorage()
        ->tableName('doctrine_migrations');
};
