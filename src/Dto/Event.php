<?php

declare(strict_types=1);

namespace App\Dto;

class Event
{
    /** @var Scope[] */
    private(set) array $scopes = [];

    public function __construct(
        readonly public int $eventId,
        readonly public string $name,
        readonly public string $startTime,
    ) {
    }

    public function addScope(Scope $scope): self
    {
        $this->scopes[sprintf('%d_%s_%d', $this->eventId, $scope->type, $scope->number)] = $scope;

        return $this;
    }
}
