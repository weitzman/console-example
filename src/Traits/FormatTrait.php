<?php

namespace App\Traits;

use App\Interfaces\FormatterConfigurationItemProviderInterface;
use Consolidation\OutputFormatters\FormatterManager;
use Consolidation\OutputFormatters\Options\FormatterOptions;
use Symfony\Component\Console\Command\Command;

trait FormatTrait {

  /**
   * Format the structured data as per user input and the command definition.
   */
  public function format($output, $input, $data, $format) {
    $formatterManager = new FormatterManager();
    $configurationData = $this->getConfigurationData($this);
    $formatterOptions = new FormatterOptions($configurationData, $input->getOptions());
    $formatterOptions->setInput($input);
    $formatterManager->write($output, $format, $data, $formatterOptions);
  }

  /**
   * Build the formatter configuration from the command's attributes
   */
  public function getConfigurationData(Command $command): array {
    $configurationData = [];
    $reflection = new \ReflectionObject($command);
    $attributes = $reflection->getAttributes();
    foreach ($attributes as $attribute) {
      $instance = $attribute->newInstance();
      if ($instance instanceof FormatterConfigurationItemProviderInterface) {
        $configurationData = array_merge($configurationData, $instance->getConfigurationItem($attribute));
      }
    }
    return $configurationData;
  }

}
