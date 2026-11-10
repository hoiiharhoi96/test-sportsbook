<?php

declare(strict_types=1);

namespace App\Dto;

class Market
{
    /**
     * @var Outcome[]
     */
    private(set) array $outcomes = [];

    public function __construct(
        readonly public string $type,
        readonly public ?float $typeParameter,
    ) {
    }

    public function addOutcome(Outcome $outcome): self
    {
        $this->outcomes[] = $outcome;

        return $this;
    }
}
