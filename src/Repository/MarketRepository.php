<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Market;
use App\Entity\Scope;
USE PDOStatement;

class MarketRepository extends AbstractRepository
{
    protected string $tableName {
        get => 'markets';
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
                Market::SCOPE_ID_FIELD_NAME,
                Market::TYPE_PARAMETER_FIELD_NAME,
                Market::TYPE_FIELD_NAME,
                $i
            ))
            |> (fn(string $i): PDOStatement => $this->db->query($i, array_merge(...$data)));
    }

    public function getIds(array $keys): array
    {
        return $keys
                |> count(...)
                |> (fn(int $i): array => array_fill(0, $i, '?'))
                |> (fn(array $i): string => implode(', ', $i))
                |> (fn(string $i): string => sprintf(
                    "select
                            CONCAT_WS('_', s.%s, m.%s),
                            m.%s
                        from %s m
                        inner join scopes s on m.%s = s.%s
                        where CONCAT_WS('_', s.%s, m.%s) in (%s)",
                    Scope::ID_FIELD_NAME,
                    Market::TYPE_FIELD_NAME,
                    Market::ID_FIELD_NAME,
                    $this->tableName,
                    Market::SCOPE_ID_FIELD_NAME,
                    Scope::ID_FIELD_NAME,
                    Scope::ID_FIELD_NAME,
                    Market::TYPE_FIELD_NAME,
                    $i,
                ))
                |> (fn(string $i): array => $this->db->fetchKeyPair($i, $keys));
    }
}
