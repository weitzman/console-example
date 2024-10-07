<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Attribute\Format;
use App\Events\ConsoleInitEvent;
use App\Traits\FormatTrait;
use Consolidation\OutputFormatters\FormatterManager;
use Consolidation\OutputFormatters\Options\FormatterOptions;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: 10)]
final class FormatListener {

  use FormatTrait;

  public function __invoke(ConsoleInitEvent $event): void {

    $application = $event->getApplication();
    foreach ($application->all() as $key => $command) {
      if ($key !== $command->getName()) {
        // This is an alias, so skip it.
        continue;
      }
      $reflection = new \ReflectionObject($command);
      $attributes = $reflection->getAttributes(Format::class);
      if (empty($attributes)) {
        continue;
      }
      $instance = $attributes[0]->newInstance();
      $formatterManager = new FormatterManager();
      $formatterManager->addDefaultFormatters();
      $formatterOptions = new FormatterOptions($this->getConfigurationData($command), $application->getDefinition()->getOptions());
      $inputOptions = $formatterManager->automaticOptions($formatterOptions, $instance->type);
      foreach ($inputOptions as $inputOption) {
        $mode = $this->getPrivatePropValue($inputOption, 'mode');
        $suggestedValues = $this->getPrivatePropValue($inputOption, 'suggestedValues');
        $command->addOption($inputOption->getName(), $inputOption->getShortcut(), $mode, $inputOption->getDescription(), $inputOption->getDefault(), $suggestedValues);
      }
    }
  }

  protected function getPrivatePropValue(mixed $object, $name): mixed {
    $rc = new \ReflectionClass($object);
    $prop = $rc->getProperty($name);
    $prop->setAccessible(true);
    return $prop->getValue($object);
  }

}
