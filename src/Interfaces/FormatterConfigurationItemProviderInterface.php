<?php

namespace App\Interfaces;

interface FormatterConfigurationItemProviderInterface {

    const KEY = '';

    public function getConfigurationItem(\ReflectionAttribute $attribute): array;

}
