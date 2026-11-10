<?php

declare(strict_types=1);

namespace App\Dto;

readonly class Outcome
{
    public function __construct(
        public int $id,
        public float $odds,
    ) {
    }
}
