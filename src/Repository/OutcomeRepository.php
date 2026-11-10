<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Outcome;
USE PDOStatement;

class OutcomeRepository extends AbstractRepository
{
    protected string $tableName {
        get => 'outcomes';
    }

    public function insertList(array $data): void
    {
        $data
            |> count(...)
            |> (fn(int $i): array => array_fill(0, $i, '(?, ?, ?)'))
            |> (fn(array $i): string => implode(', ', $i))
            |> (fn(string $i): string => sprintf(
                'INSERT INTO %s (%s, %s, %s) VALUES %s',
                $this->tableName,
                Outcome::MARKET_ID_NAME,
                Outcome::EXTERNAL_ID_FIELD_NAME,
                Outcome::ODDS_FIELD_NAME,
                $i
            ))
            |> (fn(string $i): PDOStatement => $this->db->query($i, array_merge(...$data)));
    }
}
