<?php

namespace App\Traits;

use App\Interfaces\FormatterConfigurationItemProviderInterface;
use Symfony\Component\Console\Command\Command;

trait FormatterOptionsTrait {

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
