<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Country;
use PDOStatement;

class CountryRepository extends AbstractRepository
{
    protected string $tableName {
        get => 'countries';
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
                Country::CODE_FIELD_NAME,
                Country::NAME_FIELD_NAME,
                $i,
                Country::NAME_FIELD_NAME,
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
                    'SELECT %s, %s FROM %s WHERE %s IN(%s)',
                    Country::CODE_FIELD_NAME,
                    Country::ID_FIELD_NAME,
                    $this->tableName,
                    Country::CODE_FIELD_NAME,
                    $i
                ))
                |> (fn(string $i): array => $this->db->fetchKeyPair($i, $codes));
    }
}
