<?php

declare(strict_types=1);

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcherInterfaceComponentAlias;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('path.base', dirname(__DIR__))
    ;

    $services = $container->services()
        ->defaults()
        ->public()
        ->bind('$env', '%env%')
    ;

    $container->services()
      ->set('event_dispatcher', EventDispatcher::class)
      ->public()
      ->tag('container.hot_path')
      ->tag('event_dispatcher.dispatcher', ['name' => 'event_dispatcher'])
      ->alias(EventDispatcherInterfaceComponentAlias::class, 'event_dispatcher')
      ->alias(EventDispatcherInterface::class, 'event_dispatcher')
      ->alias(PsrEventDispatcherInterface::class, 'event_dispatcher');

    // this parameter is used at compile time in RegisterListenersPass
    $container->parameters()->set('event_dispatcher.event_aliases', array_merge(
    class_exists(ConsoleEvents::class) ? ConsoleEvents::ALIASES : [],
    ));

    $services->load('App\\Commands\\', 'Commands/*/*Command.php')->tag('console.command');
    $services->load('App\\Commands\\', 'Commands/*Command.php')->tag('console.command');

    $services->load('App\\Listeners\\', 'Listeners/*Listener.php')
      ->tag('kernel.event_listener'); // ->autoconfigure(TRUE)
};
