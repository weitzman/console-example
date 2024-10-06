<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Attribute\OptionsetGetEditor;
use App\Events\ConsoleInitEvent;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final class OptionSetGetEditorListener {

  public function __invoke(ConsoleInitEvent $event): void {
    $application = $event->getApplication();
    foreach ($application->all() as $command) {
      $reflection = new \ReflectionObject($command);
      $attributes = $reflection->getAttributes(OptionsetGetEditor::class);
      if (empty($attributes)) {
        continue;
      }
      $command->addOption('editor', '', InputOption::VALUE_REQUIRED, 'A string of bash which launches user\'s preferred text editor. Defaults to <info>${VISUAL-${EDITOR-vi}}</info>.', '${VISUAL-${EDITOR-vi}}');
      $command->addOption('bg', '', InputOption::VALUE_NONE, 'Launch editor in background process.', null);
    }
  }

}
