<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Console\Route;

use App\Application\Command\Route\ImportItinerariesFromFile as ImportItinerariesFromFileCommand;
use App\Application\Commons\Command\CommandBus;

use function sprintf;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:route:itinerary:import-from-file')]
class ImportItinerariesFromFile
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(
        #[Argument(name: 'path')]
        string $path,
        OutputInterface $output,
        #[Option(name: 'truncate')]
        bool $truncate = false
    ): int {
        $output->writeln(sprintf('<info>Importing itineraries from file %s</info>', $path));

        $importRoutesFromFile = new ImportItinerariesFromFileCommand($path, $truncate);
        $this->commandBus->dispatch($importRoutesFromFile);

        $output->writeln(sprintf('<info>Successfully imported itineraries from file %s</info>', $path));

        return Command::SUCCESS;
    }
}
