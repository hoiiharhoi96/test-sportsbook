<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Parsing;
use App\Dto\Market;
use App\Dto\Outcome;
use App\Dto\Scope;

class GroupMarketsParserHandler extends AbstractParserHandler
{
    protected function parse(array $data, Parsing $context): self
    {
        $items = $data['group_markets'] ?? [];
        if ([] === $items) {
            return $this;
        }

        foreach ($items as $scopeString => $markets) {
            [$type, $number] = explode('|', $scopeString);
            $scope = new Scope(type: $type, number: (int) $number);
            $context->event->addScope($scope);

            foreach ($markets as $marketRaw) {
                $marketData = explode('|', $marketRaw);

                $market = new Market(type: $marketData[1], typeParameter: (float) $marketData[2] ?: null);
                $scope->addMarket($market);

                foreach (explode('!', $marketData[7]) as $outcomeRaw) {
                    $outcome = explode('~', $outcomeRaw);
                    $market->addOutcome(new Outcome(id: (int) $outcome[0], odds: (float) $outcome[2]));
                }
            }
        }

        return $this;
    }
}
