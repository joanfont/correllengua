<?php

namespace App\Infrastructure\Symfony\Console\Route;

use App\Application\Command\Route\ImportRoutesFromFile as ImportRoutesFromFileCommand;
use App\Application\Commons\Command\CommandBus;

use function sprintf;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:route:import-from-file')]
class ImportRoutesFromFile
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(#[\Symfony\Component\Console\Attribute\Argument(name: 'path')]
    string $path, OutputInterface $output): int
    {
        $output->writeln(sprintf('<info>Importing routes from file %s</info>', $path));

        $importRoutesFromFile = new ImportRoutesFromFileCommand($path);
        $this->commandBus->dispatch($importRoutesFromFile);

        $output->writeln(sprintf('<info>Successfully imported routes from file %s</info>', $path));

        return self::SUCCESS;
    }
}
