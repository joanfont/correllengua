<?php

namespace App\Infrastructure\Symfony\Console\Route;

use App\Application\Command\Route\ImportSegmentsFromFile as ImportSegmentsFromFileCommand;
use App\Application\Commons\Command\CommandBus;

use function sprintf;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:route:segment:import-from-file')]
class ImportSegmentsFromFile
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(#[\Symfony\Component\Console\Attribute\Argument(name: 'path')]
        string $path, OutputInterface $output): int
    {
        $output->writeln(sprintf('<info>Importing segments from file %s</info>', $path));

        $importRoutesFromFile = new ImportSegmentsFromFileCommand($path);
        $this->commandBus->dispatch($importRoutesFromFile);

        $output->writeln(sprintf('<info>Successfully imported segments from file %s</info>', $path));

        return Command::SUCCESS;
    }
}
