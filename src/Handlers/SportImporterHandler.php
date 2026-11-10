<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;
use App\Dto\Sport;
use App\Repository\SportRepository;

class SportImporterHandler extends AbstractImporterHandler
{
    public function __construct(readonly protected SportRepository $sportRepository)
    {
    }

    protected function import(Importing $context): self
    {
        if ([] === $context->structure->sports) {
            return $this;
        }

        $context->structure->sports
            |> array_values(...)
            |> (fn(array $i): array => array_map(fn(Sport $s): array => $s->values, $i))
            |> $this->sportRepository->insertList(...);

        $context->sportKeys = $this->sportRepository->getIds(array_keys($context->structure->sports));

        return $this;
    }
}
