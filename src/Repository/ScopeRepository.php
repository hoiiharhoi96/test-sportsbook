<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Scope;
USE PDOStatement;

class ScopeRepository extends AbstractRepository
{

    protected string $tableName {
        get => 'scopes';
    }

    public function deleteByEventId(array $eventIds): void
    {
        $eventIds
            |> count(...)
            |> (fn(int $i): array => array_fill(0, $i, '?'))
            |> (fn(array $i): string => implode(', ', $i))
            |> (fn(string $i): string => sprintf(
                "delete from %s where %s in (%s)",
                $this->tableName,
                Scope::EVENT_ID_FIELD_NAME,
                $i,
            ))
            |> (fn(string $i): PDOStatement => $this->db->query($i, $eventIds));
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
                Scope::EVENT_ID_FIELD_NAME,
                Scope::NUMBER_FIELD_NAME,
                Scope::TYPE_FIELD_NAME,
                $i,
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
                            CONCAT_WS('_', e.%s, s.%s, s.%s),
                            s.%s
                        from %s s
                        inner join events e on s.%s = e.%s
                        where CONCAT_WS('_', e.%s, s.%s, s.%s) in (%s)",
                    Event::EXTERNAL_FIELD_NAME,
                    Scope::TYPE_FIELD_NAME,
                    Scope::NUMBER_FIELD_NAME,
                    Scope::ID_FIELD_NAME,
                    $this->tableName,
                    Scope::EVENT_ID_FIELD_NAME,
                    Event::ID_FIELD_NAME,
                    Event::EXTERNAL_FIELD_NAME,
                    Scope::TYPE_FIELD_NAME,
                    Scope::NUMBER_FIELD_NAME,
                    $i,
                ))
                |> (fn(string $i): array => $this->db->fetchKeyPair($i, $keys));
    }
}
