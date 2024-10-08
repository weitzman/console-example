<?php

declare(strict_types=1);

namespace App\Commands;

use App\Attribute\ConfirmEnvironment;
use App\Attribute\DefaultTableFields;
use App\Attribute\FieldLabels;
use App\Attribute\FilterDefaultField;
use App\Attribute\ForbidEnvironment;
use App\Attribute\Format;
use App\Attribute\OptionsetGetEditor;
use App\Traits\FormatTrait;
use Consolidation\OutputFormatters\FormatterManager;
use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
  name: 'user:information',
  description: 'Print information about the specified user(s).',
  aliases: ['uinf']
)]
// #[Argument(name: 'names', description: 'A comma delimited list of user names.')]
// #[Option(name: 'uids', description: 'A comma delimited list of user ids to lookup (an alternative to names')]
#[Format(type: RowsOfFields::class, default: 'table')]
#[OptionsetGetEditor]
//#[InteractUserNames(choices: ['alice', 'bob', 'carol'], argumentName: 'names')]
#[FieldLabels(labels: ['name' => 'Name', 'color' => 'Color'])]
#[DefaultTableFields(fields: ['name', 'color'])]
#[FilterDefaultField(field: 'name')]
#[ConfirmEnvironment('dev')]
#[ForbidEnvironment('prod')]
// @todo Deal with usages (built-in), topics (custom).
class UserInformationCommand extends Command {

  use FormatTrait;

  public function __construct(
    private FormatterManager $formatterManager
  ) {
    parent::__construct();
  }

  public function configure(): void {
    $this
      ->addArgument('names', InputArgument::REQUIRED, 'A comma delimited list of user names.')
      ->addOption('uids', null, InputOption::VALUE_REQUIRED, 'A comma delimited list of user ids to lookup (an alternative to names')
      ->setHelp('lorem ipsum');
  }

  /**
   * @todo works, but this runs before the validation listeners.
   * thus they can't depend on interactively provided params.
   */
  public function interact(InputInterface $input, OutputInterface $output): void {
    if (empty($input->getArgument('names'))) {
      $io = new SymfonyStyle($input, $output);
      $chosen = $io->choice('Please select a user name', ['alice', 'bob', 'carol']);
      $input->setArgument('names', $chosen);
    }
  }

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);
    $names = $input->getArgument('names');
    $io->writeln("Hi $names");
    $data = new RowsOfFields([['name' => 'apple', 'color' => 'red']]);
    $this->format($output, $input, $data, $input->getOption('format'));
    return Command::SUCCESS;
  }
}
