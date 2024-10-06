#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Events\ConsoleInitEvent;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

require __DIR__ . '/../vendor/autoload.php';

$env = getenv('APP_ENV') ?: 'dev';
$application = new Application();

$container = new ContainerBuilder();
$loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../src'));
// Discover commands and listeners.
$loader->import(__DIR__ . "/../config/services_{$env}.php");

// Copies from tagged services to listeners
$container->addCompilerPass(new RegisterListenersPass());

$container->compile();

// Add commands to Application.
foreach ($container->findTaggedServiceIds('console.command') as $command => $attr) {
    $application->add($container->get($command));
}
// Add event dispatcher so that built-in events happen.
$application->setDispatcher($container->get('event_dispatcher'));

// Fire a custom event.
$container->get('event_dispatcher')->dispatch(new ConsoleInitEvent($application), ConsoleInitEvent::class);

$application->run();
