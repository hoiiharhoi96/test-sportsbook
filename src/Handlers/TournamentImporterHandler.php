<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;
use App\Repository\TournamentRepository;

class TournamentImporterHandler extends AbstractImporterHandler
{
    public function __construct(readonly protected TournamentRepository $tournamentRepository)
    {
    }

    protected function import(Importing $context): self
    {
        if ([] === $context->structure->tournaments) {
            return $this;
        }

        $context->structure->tournaments
            |> array_values(...)
            |> (fn($x) => array_map(fn($i) => [
                $context->sportKeys[$i->sport->code],
                $context->countryKeys[$i->country->code],
                $i->name,
            ], $x))
            |> $this->tournamentRepository->insertList(...);

        $tournamentKeys = $this->tournamentRepository->getIds(array_keys($context->structure->tournaments));
        foreach ($context->structure->tournaments as $tournament) {
            $context->addTournamentEvents($tournamentKeys[$tournament->id], $tournament->events);
        }

        return $this;
    }
}
