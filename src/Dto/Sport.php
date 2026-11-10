<?php

declare(strict_types=1);

namespace App\Dto;

class Sport
{
    public array $values {
        get => [$this->code, $this->name];
    }

    public function __construct(
        readonly public string $code,
        readonly public string $name,
    ) {
    }
}
