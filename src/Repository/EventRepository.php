<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\EventFilter;
use App\Entity\Event;
use PDO;
USE PDOStatement;

class EventRepository extends AbstractRepository
{
    protected string $tableName {
        get => 'events';
    }

    public function insertList(array $data): void
    {
        $data
            |> count(...)
            |> (fn(int $i): array => array_fill(0, $i, '(?, ?, ?, ?)'))
            |> (fn(array $i): string => implode(', ', $i))
            |> (fn(string $i): string => sprintf(
                'INSERT INTO %s (%s, %s, %s, %s) VALUES %s
                        AS new_data 
                        ON DUPLICATE KEY UPDATE %s = new_data.name, %s = new_data.start_time',
                $this->tableName,
                Event::EXTERNAL_FIELD_NAME,
                Event::TOURNAMENT_ID_FIELD_NAME,
                Event::NAME_FIELD_NAME,
                Event::START_TIME_FIELD_NAME,
                $i,
                Event::NAME_FIELD_NAME,
                Event::START_TIME_FIELD_NAME,
            ))
            |> (fn(string $i): PDOStatement => $this->db->query($i, array_merge(...$data)));
    }

    public function getIds(array $externalIds): array
    {
        return $externalIds
                |> count(...)
                |> (fn(int $i): array => array_fill(0, $i, '?'))
                |> (fn(array $i): string => implode(', ', $i))
                |> (fn(string $i): string => sprintf(
                    'SELECT %s, %s FROM %s WHERE %s IN(%s)',
                    Event::EXTERNAL_FIELD_NAME,
                    Event::ID_FIELD_NAME,
                    $this->tableName,
                    Event::EXTERNAL_FIELD_NAME,
                    $i
                ))
                |> (fn(string $i): array => $this->db->fetchKeyPair($i, $externalIds));
    }

    public function getIdMax(): int
    {
        return $this->db->fetchColumn(sprintf(
            'SELECT MAX(%s) FROM %s',
            Event::ID_FIELD_NAME,
            $this->tableName
        ));
    }

    public function getByFilter(EventFilter $filter): array
    {
        $result = $filter->values
                |> count(...)
                |> (fn(int $i): array => array_fill(0, $i, '?'))
                |> (fn(array $i): string => implode(', ', $i))
                |> (fn(string $i): string => sprintf('CALL GetFullEvents(%s)', $i))
                |> (fn(string $i): array => $this->db->query($i, $filter->values)->fetchAll(PDO::FETCH_COLUMN, 0));

        array_walk($result, fn(&$i) => $i = json_decode($i, true) ?? []);

        return $result;
    }
}
