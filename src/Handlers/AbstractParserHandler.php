<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Parsing;

abstract class AbstractParserHandler
{
    public ?AbstractParserHandler $next = null {
        set => $value;
    }

    public function handle(array $data, Parsing $context): void
    {
        $this->parse($data, $context)->next?->handle($data, $context);
    }

    abstract protected function parse(array $data, Parsing $context): self;
}
