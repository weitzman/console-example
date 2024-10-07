<?php

namespace App\Interfaces;

interface FormatterConfigurationItemProviderInterface {

    public static function getConfigurationItem(\ReflectionAttribute $attribute): array;

}
