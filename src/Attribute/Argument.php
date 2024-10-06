<?php

namespace App\Attribute;

use Attribute;
use Symfony\Component\Console\Input\InputOption;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Argument
{

  /**
   * @param string $name
   *   The name of the argument.
   * @param string $description
   *   A one line description.
   * @param int $mode
   * @param ?string $default
   * @param array|\Closure $suggestedValues
   *   An array of suggestions or a Closure which gets them. See https://symfony.com/blog/new-in-symfony-6-1-improved-console-autocompletion#completion-values-in-input-definitions.
   */
    public function __construct(
        public string $name,
        public string $description,
        public int $mode = InputOption::VALUE_NONE,
        public ?string $default = NULL,
        public array|\Closure $suggestedValues = []
    ) {
    }
}
