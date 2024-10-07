<?php

namespace App\Interfaces;

interface FormatterConfigurationItemProviderInterface {

    const KEY = '';

    public static function getConfigurationItem(\ReflectionAttribute $attribute): array;

}
