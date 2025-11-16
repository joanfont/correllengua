<?php

namespace App\Infrastructure\Symfony\Console\Route;

use App\Application\Command\Route\ImportSegmentsFromFile as ImportSegmentsFromFileCommand;
use App\Application\Commons\Command\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:route:segment:import-from-file')]
class ImportSegmentsFromFile extends Command
{
    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('path', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        $output->writeln(sprintf('<info>Importing segments from file %s</info>', $path));

        $importRoutesFromFile = new ImportSegmentsFromFileCommand($path);
        $this->commandBus->dispatch($importRoutesFromFile);

        $output->writeln(sprintf('<info>Successfully imported segments from file %s</info>', $path));

        return self::SUCCESS;
    }
}
