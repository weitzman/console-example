<?php

declare(strict_types=1);

namespace App\Console\Listeners;

use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class OutputEnvironmentOnTerminationListener
{
    public function __construct(
        private string $env,
    ) {}

    public function __invoke(ConsoleTerminateEvent $event): void
    {
        $io = new SymfonyStyle($event->getInput(), $event->getOutput());

        $io->info("Working in {$this->env} environment");
    }
}
