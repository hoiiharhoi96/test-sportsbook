<?php

declare(strict_types=1);

namespace App\Services;

use App\Context\Importing;
use App\Dto\Structure;
use App\Interfaces\ImportInterface;

readonly class ImporterService implements ImportInterface
{
    public function __construct(private ImporterChainManagerService $importerChainManagerService)
    {
    }

    public function import(Structure $structure): void
    {
        $this->importerChainManagerService->import(new Importing($structure));
    }
}
