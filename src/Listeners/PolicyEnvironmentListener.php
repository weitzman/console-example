<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Attribute\ConfirmEnvironment;
use App\Attribute\ForbidEnvironment;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: ConsoleEvents::COMMAND)]
final readonly class PolicyEnvironmentListener
{
    public function __construct(
        private string $env,
    ) {}

    public function __invoke(ConsoleCommandEvent $event): void
    {
        $io = new SymfonyStyle($event->getInput(), $event->getOutput());

        $command = $event->getCommand();
        $reflection = new \ReflectionObject($command);

        $attributes = $reflection->getAttributes(ForbidEnvironment::class);

        foreach ($attributes as $attribute) {
            /** @var ForbidEnvironment $forbidEnvironment */
            $forbidEnvironment = $attribute->newInstance();

            if ($forbidEnvironment->env !== $this->env) {
                continue;
            }

            $io->error($forbidEnvironment->message);
            $event->disableCommand();
            return;
        }

        $attributes = $reflection->getAttributes(ConfirmEnvironment::class);

        foreach ($attributes as $attribute) {
            /** @var ConfirmEnvironment $confirmEnvironment */
            $confirmEnvironment = $attribute->newInstance();

            if ($confirmEnvironment->env !== $this->env) {
                continue;
            }

            $confirm = $io->confirm($confirmEnvironment->message, false);

            if (!$confirm) {
                $io->info('User cancelled the operation.');
                $event->disableCommand();
                return;
            }
        }
    }
}
