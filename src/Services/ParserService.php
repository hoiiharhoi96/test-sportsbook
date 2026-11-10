<?php

declare(strict_types=1);

namespace App\Services;

use App\Context\Parsing;
use App\Dto\Structure;
use App\Interfaces\ParserInterface;

readonly class ParserService implements ParserInterface
{
    public function __construct(private ParserChainManagerService $parserChainManagerService)
    {
    }

    public function parse(array $data): Structure
    {
        $structure = new Structure();

        foreach ($data['events'] ?? [] as $item) {
            $this->parserChainManagerService->parse($item, new Parsing($structure));
        }

        return $structure;
    }
}
