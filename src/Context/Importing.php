<?php

declare(strict_types=1);

namespace App\Context;

use App\Dto\Event;
use App\Dto\Market;
use App\Dto\Outcome;
use App\Dto\Scope;
use App\Dto\Structure;

class Importing
{
    public array $sportKeys {
        set => $value;
    }

    public array $countryKeys {
        set => $value;
    }

    /**
     * @var array<int, array<int, Event>> $tournamentEvents
     */
    private(set) array $tournamentEvents = [];

    /**
     * @var array<int, array<int, Scope>> $eventScopes
     */
    private(set) array $eventScopes = [];

    /**
     * @var array<int, array<int, Market>> $scopeMarkets
     */
    private(set) array $scopeMarkets = [];

    /**
     * @var array<int, array<int, Outcome>> $marketOutcomes
     */
    private(set) array $marketOutcomes = [];

    public function __construct(readonly public Structure $structure)
    {
    }

    /**
     * @param Event[] $events
     */
    public function addTournamentEvents(int $id, array $events): void
    {
        $this->tournamentEvents[$id] = $events;
    }

    /**
     * @param Scope[] $scopes
     */
    public function addEventScopes(int $id, array $scopes): void
    {
        $this->eventScopes[$id] = $scopes;
    }

    /**
     * @param Market[] $markets
     */
    public function addScopeMarkets(int $id, array $markets): void
    {
        $this->scopeMarkets[$id] = $markets;
    }

    /**
     * @param Outcome[] $outcomes
     */
    public function addMarketOutcomes(int $id, array $outcomes): void
    {
        $this->marketOutcomes[$id] = $outcomes;
    }
}
