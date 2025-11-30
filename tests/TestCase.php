<?php

namespace App\Tests;

use App\Application\Commons\Command\Command;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Event\Event;
use App\Application\Commons\Query\Query;
use App\Application\Commons\Query\QueryBus;

use function assert;
use function implode;
use function is_object;

use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

class TestCase extends KernelTestCase
{
    protected static function handleCommand(Command $command): void
    {
        $commandBus = static::get(CommandBus::class);
        assert($commandBus instanceof CommandBus);
        $commandBus->dispatch($command);
    }

    /**
     * @template T
     *
     * @param Query<T> $query
     *
     * @return T
     */
    protected static function handleQuery(Query $query): mixed
    {
        $queryBus = static::get(QueryBus::class);
        assert($queryBus instanceof QueryBus);

        return $queryBus->query($query);
    }

    protected static function get(string $id): mixed
    {
        return static::getContainer()->get($id);
    }

    protected static function set(string $id, mixed $value): void
    {
        if (is_object($value)) {
            static::getContainer()->set($id, $value);
        }
    }

    protected static function getParameter(string $name): mixed
    {
        return static::getContainer()->getParameter($name);
    }

    protected static function setParameter(string $name, string $value): void
    {
        static::getContainer()->setParameter($name, $value);
    }

    /**
     * @param class-string|null $class
     *
     * @return array<Event>
     */
    public static function events(?string $class = null): array
    {
        /** @var TransportInterface $transport */
        $transport = static::getContainer()->get('messenger.transport.async');

        $events = [];
        foreach ($transport->get() as $envelope) {
            $message = $envelope->getMessage();
            if (!$message instanceof Event) {
                continue;
            }

            if (null === $class) {
                $events[] = $message;
            } elseif ($message::class === $class) {
                $events[] = $message;
            }
        }

        return $events;
    }

    public static function asset(string $path): SplFileInfo
    {
        $projectDir = self::getParameter('kernel.project_dir');
        $fullPath = implode(DIRECTORY_SEPARATOR, [
            $projectDir,
            'tests',
            'Assets',
            $path,
        ]);

        return new SplFileInfo($fullPath);
    }
}
