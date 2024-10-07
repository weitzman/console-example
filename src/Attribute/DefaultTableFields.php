<?php

namespace App\Attribute;

use App\Interfaces\FormatterConfigurationItemProviderInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_CLASS)]
class DefaultTableFields implements FormatterConfigurationItemProviderInterface
{
    /**
     * @param $fields
     *   An array of field names to show by default when using table formatter.
     */
    public function __construct(
        public array $fields,
    ) {
    }

    public static function getConfigurationItem(\ReflectionAttribute $attribute): array
    {
        $args = $attribute->getArguments();
        return ['default-table-fields' => $args['fields']];
    }
}
