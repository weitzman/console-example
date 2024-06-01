<?php

declare(strict_types=1);

namespace App\Console\Listeners;

use App\Attribute\ConfirmEnvironmentAttribute;
use App\Attribute\ForbidEnvironmentAttribute;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class ControlExecutionOnCommandListener
{
    public function __construct(
        private string $env,
    ) {}

    public function __invoke(ConsoleCommandEvent $event): void
    {
        $io = new SymfonyStyle($event->getInput(), $event->getOutput());

        $command = $event->getCommand();
        $reflection = new \ReflectionObject($command);

        $attributes = $reflection->getAttributes(ForbidEnvironmentAttribute::class);

        foreach ($attributes as $attribute) {
            /** @var ForbidEnvironmentAttribute $forbidEnvironment */
            $forbidEnvironment = $attribute->newInstance();

            if ($forbidEnvironment->env !== $this->env) {
                continue;
            }

            $io->error($forbidEnvironment->message);
            exit(Command::FAILURE);
        }

        $attributes = $reflection->getAttributes(ConfirmEnvironmentAttribute::class);

        foreach ($attributes as $attribute) {
            /** @var ConfirmEnvironmentAttribute $confirmEnvironment */
            $confirmEnvironment = $attribute->newInstance();

            if ($confirmEnvironment->env !== $this->env) {
                continue;
            }

            $confirm = $io->confirm($confirmEnvironment->message, false);

            if (!$confirm) {
                $io->info('User cancelled the operation.');
                exit(Command::SUCCESS);
            }
        }
    }
}
