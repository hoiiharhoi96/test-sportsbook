<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Dto\Structure;

interface ParserInterface
{
    public function parse(array $data): Structure;
}