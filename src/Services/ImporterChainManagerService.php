<?php

declare(strict_types=1);

namespace App\Services;

use App\Context\Importing;
use App\Handlers\AbstractImporterHandler;

class ImporterChainManagerService extends AbstractChainManagerService
{
    protected AbstractImporterHandler $first {
        get => $this->internalFirst;
    }

    public function import(Importing $context): void
    {
        $this->first->handle($context);
    }
}
