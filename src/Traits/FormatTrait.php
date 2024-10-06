<?php

namespace App\Traits;

use Consolidation\OutputFormatters\FormatterManager;
use Consolidation\OutputFormatters\Options\FormatterOptions;

trait FormatTrait {

  public function format($output, $data, $format) {
    $formatterManager = new FormatterManager();
    // @todo deal with formatter options.
    $formatterManager->write($output, $format, $data, new FormatterOptions());
  }

}
