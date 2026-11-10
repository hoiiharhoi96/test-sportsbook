<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Event;
use App\Entity\Sport;
use App\Entity\Tournament;
use PDOStatement;

class TournamentRepository extends AbstractRepository
{

    protected string $tableName {
        get => 'tournaments';
    }

    public function insertList(array $data): void
    {
        $data
            |> count(...)
            |> (fn(int $i): array => array_fill(0, $i, '(?, ?, ?)'))
            |> (fn(array $i): string => implode(', ', $i))
            |> (fn(string $i): string => sprintf(
                'INSERT INTO %s (%s, %s, %s) VALUES %s
                        AS new_data 
                        ON DUPLICATE KEY UPDATE %s = new_data.name',
                $this->tableName,
                Tournament::SPORT_ID_FIELD_NAME,
                Tournament::COUNTRY_ID_FIELD_NAME,
                Tournament::NAME_FIELD_NAME,
                $i,
                Tournament::NAME_FIELD_NAME,
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
                            CONCAT(c.%s, '_', s.%s),
                            t.%s
                        from %s t
                        inner join sports s on t.%s = s.%s
                        inner join countries c on t.%s = c.%s
                        where CONCAT(c.%s, '_', s.%s) in (%s)",
                    Country::CODE_FIELD_NAME,
                    Sport::CODE_FIELD_NAME,
                    Tournament::ID_FIELD_NAME,
                    $this->tableName,
                    Tournament::SPORT_ID_FIELD_NAME,
                    Sport::ID_FIELD_NAME,
                    Tournament::COUNTRY_ID_FIELD_NAME,
                    Country::ID_FIELD_NAME,
                    Country::CODE_FIELD_NAME,
                    Sport::CODE_FIELD_NAME,
                    $i,
                ))
                |> (fn(string $i): array => $this->db->fetchKeyPair($i, $keys));
    }

    public function getEvetRich(): array
    {
        return $this->db->fetchAssoc(sprintf(
            "select
                        t.%s,
                        t.%s
                    from %s t
                    inner join sports s on t.%s = s.%s
                    inner join events e on t.%s = e.%s
                    where s.%s = 'SOCCER'
                    group by t.%s
                    order by count(*) DESC
                    limit 10",
            Tournament::ID_FIELD_NAME,
            Tournament::NAME_FIELD_NAME,
            $this->tableName,
            Tournament::SPORT_ID_FIELD_NAME,
            Sport::ID_FIELD_NAME,
            Tournament::ID_FIELD_NAME,
            Event::TOURNAMENT_ID_FIELD_NAME,
            Sport::CODE_FIELD_NAME,
            Tournament::ID_FIELD_NAME,
        ));
    }
}
