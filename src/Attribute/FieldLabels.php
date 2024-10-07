<?php

namespace App\Attribute;

use App\Interfaces\FormatterConfigurationItemProviderInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class FieldLabels implements FormatterConfigurationItemProviderInterface
{
    /**
     * @param $labels
     *   An associative array of field names and labels for display.
     */
    public function __construct(
        public array $labels
    ) {
    }

  public static function getConfigurationItem(\ReflectionAttribute $attribute): array
  {
    $args = $attribute->getArguments();
    return ['field-labels' => $args['labels']];
  }
}
