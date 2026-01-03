<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\DependencyInjection;

use function array_keys;
use function assert;
use function is_array;
use function is_string;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterDoctrineTypesCompilerPass implements CompilerPassInterface
{
    private const string TYPE_DEFINITION_PARAMETER = 'doctrine.dbal.connection_factory.types';

    private const string TAG_NAME = 'app.doctrine.type';

    public function process(ContainerBuilder $container): void
    {
        $doctrineTypesParam = $container->getParameter(self::TYPE_DEFINITION_PARAMETER);
        if (!is_array($doctrineTypesParam)) {
            $doctrineTypesParam = [];
        }

        /** @var array<string, array<string, mixed>> $doctrineTypes */
        $doctrineTypes = $doctrineTypesParam;

        $types = $container->findTaggedServiceIds(self::TAG_NAME);
        foreach (array_keys($types) as $type) {
            assert(is_string($type));

            $typeName = $type::name();
            assert(is_string($typeName));

            $doctrineTypes[$typeName] = ['class' => $type];
        }

        $container->setParameter(self::TYPE_DEFINITION_PARAMETER, $doctrineTypes);
    }
}
