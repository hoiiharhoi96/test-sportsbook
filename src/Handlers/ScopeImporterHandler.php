<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;
use App\Repository\ScopeRepository;

class ScopeImporterHandler extends AbstractImporterHandler
{
    public function __construct(readonly protected ScopeRepository $scopeRepository)
    {
    }

    protected function import(Importing $context): self
    {
        if ([] === $context->eventScopes) {
            return $this;
        }

        $data = [];
        $allScopes = [];
        foreach ($context->eventScopes as $eventId => $scopes) {
            foreach ($scopes as $id => $scope) {
                $data[$id] = [
                    $eventId,
                    $scope->number,
                    $scope->type,
                ];

                $allScopes[$id] = $scope;
            }
        }

        $this->scopeRepository->deleteByEventId(array_keys($context->eventScopes));

        $scopeKeys = [];
        foreach (array_chunk($data, 500, true) as $scopes) {
            $this->scopeRepository->insertList(array_values($scopes));
            $scopeKeys += $this->scopeRepository->getIds(array_keys($scopes));
        }

        foreach ($allScopes as $id => $scope) {
            $context->addScopeMarkets($scopeKeys[$id], $scope->markets);
        }

        return $this;
    }
}
