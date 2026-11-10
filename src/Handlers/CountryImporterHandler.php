<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;
use App\Dto\Country;
use App\Repository\CountryRepository;

class CountryImporterHandler extends AbstractImporterHandler
{
    public function __construct(readonly protected CountryRepository $countryRepository)
    {
    }

    protected function import(Importing $context): self
    {
        if ([] === $context->structure->countries) {
            return $this;
        }

        $context->structure->countries
            |> array_values(...)
            |> (fn(array $i): array => array_map(fn(Country $s): array => $s->values, $i))
            |> $this->countryRepository->insertList(...);

        $context->countryKeys = $this->countryRepository->getIds(array_keys($context->structure->countries));

        return $this;
    }
}
