<?php

namespace App\Attribute;

use App\Interfaces\FormatterConfigurationItemProviderInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class FilterDefaultField implements FormatterConfigurationItemProviderInterface
{
    /**
     * @param $field
     *   A field name to filter on by default.
     */
    public function __construct(
        public string $field
    ) {
    }

  public static function getConfigurationItem(\ReflectionAttribute $attribute): array
  {
    $args = $attribute->getArguments();
    return ['filter-default-field' => $args['field']];
  }
}
