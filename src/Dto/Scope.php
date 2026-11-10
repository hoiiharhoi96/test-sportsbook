<?php

declare(strict_types=1);

namespace App\Dto;

class Scope
{
    /**
     * @var Market[] $markets
     */
    private(set) array $markets = [];

    public function __construct(
        readonly public string $type,
        readonly public int $number,
    ) {
    }
    public function addMarket(Market $market): self
    {
        $this->markets[] = $market;

        return $this;
    }
}
