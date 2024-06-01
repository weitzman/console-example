<?php

declare(strict_types=1);

namespace App\Console;

use App\Attribute\ConfirmEnvironmentAttribute;
use App\Attribute\ForbidEnvironmentAttribute;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[
    AsCommand(
        name: 'bar',
        description: 'Bar command',
    )
]
#[ForbidEnvironmentAttribute('prod')]
#[ForbidEnvironmentAttribute('qa', message: 'This command is not compatible with the qa environment.')]
#[ConfirmEnvironmentAttribute('dev', message: 'Confirm you know what you are doing.')]
final  class BarCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success("Hello Bar");

        return Command::SUCCESS;
    }
}
