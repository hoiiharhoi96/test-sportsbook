<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;

abstract class AbstractImporterHandler
{
    public ?AbstractImporterHandler $next = null {
        set => $value;
    }

    public function handle(Importing $context): void
    {
        $this->import($context)->next?->handle($context);
    }

    abstract protected function import(Importing $context): self;
}
