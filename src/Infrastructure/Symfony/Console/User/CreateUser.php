<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Console\User;

use App\Application\Command\User\CreateUser as CreateUserCommand;
use App\Application\Commons\Command\CommandBus;

use function sprintf;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'user:create')]
class CreateUser extends Command
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $createUser = new CreateUserCommand(
            email: $email,
            password: $password,
        );

        $this->commandBus->dispatch($createUser);

        $io->success(sprintf('User "%s" created successfully', $email));

        return Command::SUCCESS;
    }
}
