<?php

declare(strict_types=1);

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class ConfirmEnvironment
{
    public string $message;

    public function __construct(
        public string $env,
        string $message = 'Do you want to run the command in {{env}} environment.',
    ) {
        $this->message = str_replace('{{env}}', $env, $message);
    }
}
