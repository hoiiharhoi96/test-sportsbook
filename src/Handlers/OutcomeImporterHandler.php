<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;
use App\Repository\OutcomeRepository;

class OutcomeImporterHandler extends AbstractImporterHandler
{
    public function __construct(readonly protected OutcomeRepository $outcomeRepository)
    {
    }

    protected function import(Importing $context): self
    {
        if ([] === $context->marketOutcomes) {
            return $this;
        }

        $data = [];
        foreach ($context->marketOutcomes as $marketId => $outcomes) {
            foreach ($outcomes as $outcome) {
                $data[] = [
                    $marketId,
                    $outcome->id,
                    $outcome->odds,
                ];
            }
        }

        foreach (array_chunk($data, 500, true) as $outcomes) {
            $this->outcomeRepository->insertList($outcomes);
        }

        return $this;
    }
}
