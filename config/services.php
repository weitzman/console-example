<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('path.base', dirname(__DIR__))
    ;

    $services = $container->services()
        ->defaults()
        ->public()
        ->bind('$env', '%env%')
    ;

    $services->load('App\\Console\\', 'Console/*/*Command.php')->tag('console.command');
    $services->load('App\\Console\\', 'Console/*Command.php')->tag('console.command');
    $services->load('App\\Console\\Listeners\\', 'Console/Listeners/*OnCommandListener.php')
        ->tag('kernel.event_listener', ['event' => 'console.command']);
    $services->load('App\\Console\\Listeners\\', 'Console/Listeners/*OnTerminationListener.php')
        ->tag('kernel.event_listener', ['event' => 'console.terminate']);
};
