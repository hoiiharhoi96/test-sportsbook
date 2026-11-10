<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

abstract class AbstractRepository
{
    abstract protected string $tableName { get; }

    public function __construct(protected Database $db)
    {
    }
}
