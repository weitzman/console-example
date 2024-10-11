<?php

namespace App\Traits;

use Consolidation\OutputFormatters\Options\FormatterOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * It is assumed that $this->formatterManager is available on the class using this trait.
 */
trait FormatTrait {

  use FormatterOptionsTrait;

  /**
   * Format the structured data as per user input and the command definition.
   */
  public function execute(InputInterface $input, OutputInterface $output): int {
    $configurationData = $this->getConfigurationData($this);
    $formatterOptions = new FormatterOptions($configurationData, $input->getOptions());
    $formatterOptions->setInput($input);
    $data = $this->doExecute($input, $output);
    $this->formatterManager->write($output, $input->getOption('format'), $data, $formatterOptions);
    return Command::SUCCESS;
  }

  /**
   * Override this method with the actual command logic. Type hint the return value
   * to help the formatter know what to expect.
   */
  abstract protected function doExecute(InputInterface $input, OutputInterface $output);
}
