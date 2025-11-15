<?php

namespace App\Tests;

use App\Application\Commons\Command\Command;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\Query;
use App\Application\Commons\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestCase extends KernelTestCase
{
    protected static function handleCommand(Command $command): void
    {
        static::get(CommandBus::class)->dispatch($command);
    }

    protected static function handleQuery(Query $query): mixed
    {
        return static::get(QueryBus::class)->query($query);
    }

    protected static function get(string $id): mixed
    {
        return static::getContainer()->get($id);
    }

    protected static function set(string $id, mixed $value): void
    {
        static::getContainer()->set($id, $value);
    }

    public function setParameter(string $name, string $value): void
    {
        static::getContainer()->setParameter($name, $value);
    }
}