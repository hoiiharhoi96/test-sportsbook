<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Parsing;
use App\Dto\Sport;

class SportParserHandler extends AbstractParserHandler
{
    protected function parse(array $data, Parsing $context): self
    {
        $item = $data['data']['sport'] ?? [];
        if ([] === $item) {
            return $this;
        }

        $sport = $context->structure->getSport($item['id']);
        if (null === $sport) {
            $sport = new Sport(code: $item['id'], name: $item['name']);
            $context->structure->addSport($sport);
        }

        $context->sport = $sport;

        return $this;
    }
}
