<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;
use App\Repository\MarketRepository;

class MarketImporterHandler extends AbstractImporterHandler
{
    public function __construct(readonly protected MarketRepository $marketRepository)
    {
    }

    protected function import(Importing $context): self
    {
        if ([] === $context->scopeMarkets) {
            return $this;
        }

        $data = [];
        $allMarkets = [];
        foreach ($context->scopeMarkets as $scopeId => $markets) {
            foreach ($markets as $market) {
                $id = sprintf('%d_%s', $scopeId, $market->type);

                $data[$id] = [
                    $scopeId,
                    $market->typeParameter,
                    $market->type,
                ];

                $allMarkets[$id] = $market;
            }
        }

        $marketKeys = [];
        foreach (array_chunk($data, 500, true) as $markets) {
            $this->marketRepository->insertList(array_values($markets));
            $marketKeys += $this->marketRepository->getIds(array_keys($markets));
        }

        foreach ($allMarkets as $id => $market) {
            $context->addMarketOutcomes($marketKeys[$id], $market->outcomes);
        }

        return $this;
    }
}
