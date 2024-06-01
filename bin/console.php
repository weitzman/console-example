#!/usr/bin/env php
<?php

declare(strict_types=1);

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;

require __DIR__ . '/../vendor/autoload.php';

$env = getenv('APP_ENV') ?: 'dev';
$application = new Application();

$container = new ContainerBuilder();
$loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../src'));
$loader->import(__DIR__ . "/../config/services_{$env}.php");

$container->compile();

foreach ($container->findTaggedServiceIds('console.command') as $command => $attr) {
    $application->add($container->get($command));
}

$dispatcher = new EventDispatcher();
foreach ($container->findTaggedServiceIds('kernel.event_listener') as $listener => $attr) {
    $dispatcher->addListener($attr[0]['event'], $container->get($listener));
}
$application->setDispatcher($dispatcher);

$application->run();
