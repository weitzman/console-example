<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Attribute\Argument;
use App\Attribute\Option;
use App\Events\ConsoleInitEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: 10)]
final class DefinitionListener {

  public function __invoke(ConsoleInitEvent $event): void {

    $application = $event->getApplication();
    foreach ($application->all() as $key => $command) {
      if ($key !== $command->getName()) {
        // This is an alias, so skip it.
        continue;
      }
      $reflection = new \ReflectionObject($command);
      $attributes = $reflection->getAttributes(Argument::class);
      foreach ($attributes as $attribute) {
        $instance = $attribute->newInstance();
        $command->addArgument($instance->name, $instance->mode, $instance->description, $instance->default, $instance->suggestedValues);
      }
      $attributes = $reflection->getAttributes(Option::class);
      foreach ($attributes as $attribute) {
        $instance = $attribute->newInstance();
        $command->addOption($instance->name, $instance->shortcut, $instance->mode, $instance->description, $instance->default, $instance->suggestedValues);
      }
    }
  }

}
