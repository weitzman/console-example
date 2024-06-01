<?php

declare(strict_types=1);

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class ForbidEnvironmentAttribute
{
    public string $message;

    public function __construct(
        public string $env,
        string $message = 'This command is not allowed in {{env}} environment.',
    ) {
        $this->message = str_replace('{{env}}', $env, $message);
    }
}
