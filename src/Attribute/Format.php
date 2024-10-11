<?php

namespace App\Attribute;

use Attribute;
use Consolidation\AnnotatedCommand\Parser\CommandInfo;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Format
{

  /**
   * @param ?string $default
   *   The default output format.
   * @param ?string $type
   * *   The data type that your command produces.
 */
    public function __construct(
        public ?string $default = null,
        public ?string $type = null,
    ) {
    }
}
