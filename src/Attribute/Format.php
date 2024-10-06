<?php

namespace App\Attribute;

use Attribute;
use Consolidation\AnnotatedCommand\Parser\CommandInfo;
use Symfony\Component\Console\Input\InputOption;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Format
{

  /**
   * @param $type
   *   The data type that your command produces.
   * @param ?string $default
   *   The default ouutput format.
   */
    public function __construct(
        public string $type,
        public ?string $default,
    ) {
    }
}
