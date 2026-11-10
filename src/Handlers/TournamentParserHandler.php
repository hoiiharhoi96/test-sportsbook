<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Parsing;
use App\Dto\Tournament;

class TournamentParserHandler extends AbstractParserHandler
{
    protected function parse(array $data, Parsing $context): self
    {
        $item = $data['data']['tournament'] ?? [];
        if ([] === $item) {
            return $this;
        }

        $tournament = $context->structure->getTournament(
            sprintf('%s_%s', $context->country->code, $context->sport->code)
        );
        if (null === $tournament) {
            $tournament = new Tournament(
                name: $item['name'],
                country: $context->country,
                sport: $context->sport,
            );
            $context->structure->addTournament($tournament);
        }

        $context->tournament = $tournament;

        return $this;
    }
}
