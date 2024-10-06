<?php

/*
 * A custom event, mainly for command info altering.
 */

namespace App\Events;

use Symfony\Component\Console\Application;
use Symfony\Contracts\EventDispatcher\Event;

final class ConsoleInitEvent extends Event
{
    public function __construct(
        private Application $application,
    ) {}

    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }
}
