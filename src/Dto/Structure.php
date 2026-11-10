<?php

declare(strict_types=1);

namespace App\Dto;

class Structure
{
    /**
     * @var Sport[]
     */
    private(set) array $sports = [];

    /**
     * @var Country[]
     */
    private(set) array $countries = [];

    /**
     * @var Tournament[]
     */
    private(set) array $tournaments = [];

    public function getSport(string $code): ?Sport
    {
        return $this->sports[$code] ?? null;
    }

    public function addSport(Sport $sport): self
    {
        $this->sports[$sport->code] = $sport;

        return $this;
    }

    public function getCountry(string $code): ?Country
    {
        return $this->countries[$code] ?? null;
    }

    public function addCountry(Country $country): self
    {
        $this->countries[$country->code] = $country;

        return $this;
    }

    public function getTournament(string $id): ?Tournament
    {
        return $this->tournaments[$id] ?? null;
    }

    public function addTournament(Tournament $tournament): self
    {
        $this->tournaments[$tournament->id] = $tournament;

        return $this;
    }
}
