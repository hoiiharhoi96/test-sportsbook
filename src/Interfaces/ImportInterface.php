<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Dto\Structure;

interface ImportInterface
{
    public function import(Structure $structure): void;
}