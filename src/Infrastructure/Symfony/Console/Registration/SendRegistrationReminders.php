<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Console\Registration;

use App\Application\Service\Notification\RegistrationReminderNotification;
use App\Domain\Provider\Registration\RegistrationProvider;
use DateTimeImmutable;

use function sprintf;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'registration:send-reminders', description: 'Send reminder emails for routes happening in 5 days')]
class SendRegistrationReminders extends Command
{
    private const DAYS_BEFORE = 5;

    public function __construct(
        private readonly RegistrationProvider $registrationProvider,
        private readonly RegistrationReminderNotification $reminderNotification,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('date', InputArgument::OPTIONAL, 'Target route date (Y-m-d). Defaults to 5 days from now.')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Only send to participant with this email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dateArg = $input->getArgument('date');
        $targetDate = null !== $dateArg
            ? new DateTimeImmutable($dateArg)
            : new DateTimeImmutable(sprintf('+%d days', self::DAYS_BEFORE));

        $io->info(sprintf('Sending reminders for routes on %s', $targetDate->format('Y-m-d')));

        $groupedRegistrations = $this->registrationProvider->findGroupedByParticipantForRouteDate($targetDate);

        if ([] === $groupedRegistrations) {
            $io->info('No registrations found for the target date.');

            return Command::SUCCESS;
        }

        $emailFilter = $input->getOption('email');

        $sent = 0;
        foreach ($groupedRegistrations as $registrations) {
            if (null !== $emailFilter && $registrations[0]->participant->email !== $emailFilter) {
                continue;
            }

            $this->reminderNotification->send($registrations);
            ++$sent;
        }

        $io->success(sprintf('Sent %d reminder email(s).', $sent));

        return Command::SUCCESS;
    }
}
