<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Sport;
use App\Entity\Tournament;
use PDOStatement;

class SportRepository extends AbstractRepository
{
    protected string $tableName {
        get => 'sports';
    }

    public function insertList(array $data): void
    {
        $data
            |> count(...)
            |> (fn(int $i): array => array_fill(0, $i, '(?, ?)'))
            |> (fn(array $i): string => implode(', ', $i))
            |> (fn(string $i): string => sprintf(
                'INSERT INTO %s (%s, %s) VALUES %s
                        AS new_data 
                        ON DUPLICATE KEY UPDATE %s = new_data.name',
                $this->tableName,
                Sport::CODE_FIELD_NAME,
                Sport::NAME_FIELD_NAME,
                $i,
                Sport::NAME_FIELD_NAME,
            ))
            |> (fn(string $i): PDOStatement => $this->db->query($i, array_merge(...$data)));
    }

    public function getIds(array $codes): array
    {
        return $codes
                |> count(...)
                |> (fn(int $i): array => array_fill(0, $i, '?'))
                |> (fn(array $i): string => implode(', ', $i))
                |> (fn(string $i): string => sprintf(
                    'SELECT %s, %s FROM %s WHERE %s IN (%s)',
                    Sport::CODE_FIELD_NAME,
                    Sport::ID_FIELD_NAME,
                    $this->tableName,
                    Sport::CODE_FIELD_NAME,
                    $i
                ))
                |> (fn(string $i): array => $this->db->fetchKeyPair($i, $codes));
    }

    public function getEventCount(): array
    {
        return $this->db->fetchAssoc(sprintf(
            "select
                        s.%s,
                        s.%s,
                        count(*) as count
                    from %s s
                    inner join tournaments t on s.%s = t.%s
                    inner join events e on t.%s = e.%s
                    group by s.%s
                    order by count(*) DESC",
            Sport::ID_FIELD_NAME,
            Sport::NAME_FIELD_NAME,
            $this->tableName,
            Sport::ID_FIELD_NAME,
            Tournament::SPORT_ID_FIELD_NAME,
            Tournament::ID_FIELD_NAME,
            Event::TOURNAMENT_ID_FIELD_NAME,
            Sport::ID_FIELD_NAME,
        ));
    }
}
