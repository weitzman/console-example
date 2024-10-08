<?php

declare(strict_types=1);

namespace App\Commands;

use App\Attribute\Argument;
use App\Attribute\ConfirmEnvironment;
use App\Attribute\ForbidEnvironment;
use App\Attribute\Format;
use App\Attribute\Option;
use App\Attribute\OptionsetGetEditor;
use App\Traits\FormatTrait;
use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
  name: 'user:information',
  description: 'Print information about the specified user(s).',
  aliases: ['uinf']
)]
#[Argument(name: 'names', description: 'A comma delimited list of user names.')]
#[Option(name: 'uids', description: 'A comma delimited list of user ids to lookup (an alternative to names')]
// @todo Send table default fields to formatter.
#[Format(type: RowsOfFields::class, default: 'table')]
#[OptionsetGetEditor]
#[ConfirmEnvironment('dev')]
#[ForbidEnvironment('prod')]
// @todo Deal with usages, topics.
class UserInformationCommand extends Command {

  use FormatTrait;

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    $data = new RowsOfFields([['name' => 'apple', 'color' => 'red']]);
    $this->format($output, $data, $input->getOption('format'));
    return Command::SUCCESS;
  }
}
