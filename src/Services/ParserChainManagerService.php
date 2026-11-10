<?php

declare(strict_types=1);

namespace App\Services;

use App\Context\Parsing;
use App\Handlers\AbstractParserHandler;

class ParserChainManagerService extends AbstractChainManagerService
{
    protected AbstractParserHandler $first {
        get => $this->internalFirst;
    }

    public function parse(array $data, Parsing $context): void
    {
        $this->first->handle($data, $context);
    }
}
