<?php

namespace App\Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterDoctrineTypesCompilerPass implements CompilerPassInterface
{
    private const string TYPE_DEFINITION_PARAMETER = 'doctrine.dbal.connection_factory.types';
    private const string TAG_NAME = 'app.doctrine.type';

    public function process(ContainerBuilder $container): void
    {
        $doctrineTypes = $container->getParameter(self::TYPE_DEFINITION_PARAMETER);

        $types = $container->findTaggedServiceIds(self::TAG_NAME);
        foreach ($types as $type => $_) {
            $typeName = $type::name();
            $doctrineTypes[$typeName] = ['class' => $type];
        }

        $container->setParameter(self::TYPE_DEFINITION_PARAMETER, $doctrineTypes);
    }
}