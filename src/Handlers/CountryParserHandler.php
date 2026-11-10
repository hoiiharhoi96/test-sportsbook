<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Parsing;
use App\Dto\Country;

class CountryParserHandler extends AbstractParserHandler
{
    protected function parse(array $data, Parsing $context): self
    {
        $item = $data['data']['country'] ?? [];
        if ([] === $item) {
            return $this;
        }

        $country = $context->structure->getCountry($item['id']);
        if (null === $country) {
            $country = new Country(code: $item['id'], name: $item['name']);
            $context->structure->addCountry($country);
        }

        $context->country = $country;

        return $this;
    }
}
