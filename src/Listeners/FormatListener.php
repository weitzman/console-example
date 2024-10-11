<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Attribute\Format;
use App\Events\ConsoleInitEvent;
use App\Traits\FormatterOptionsTrait;
use Consolidation\OutputFormatters\FormatterManager;
use Consolidation\OutputFormatters\Options\FormatterOptions;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: 10)]
class FormatListener {

  use FormatterOptionsTrait;

  public function __construct(
    private FormatterManager $formatterManager
  ) {}

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
      $formatterOptions = new FormatterOptions($this->getConfigurationData($command), $application->getDefinition()->getOptions());
      $reflection = new \ReflectionMethod($command, 'doExecute');
      $inputOptions = $this->formatterManager->automaticOptions($formatterOptions, $instance->type ?: $reflection->getReturnType());
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
    return $prop->getValue($object);
  }

}
