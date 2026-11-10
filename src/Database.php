<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOStatement;

readonly class Database
{
    public function __construct(private PDO $pdo)
    {
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        $result = $this->pdo->prepare($sql);
        $result->execute($params);

        return $result;
    }

    public function fetchKeyPair(string $sql, array $params = []): array
    {
        return $this->fetchAll($sql, $params, PDO::FETCH_KEY_PAIR);
    }

    public function fetchAll(string $sql, array $params = [], int $mode = PDO::FETCH_DEFAULT): array
    {
        return $this->query($sql, $params)->fetchAll($mode);
    }

    public function fetchAssoc(string $sql, array $params = []): array
    {
        return $this->fetchAll($sql, $params, PDO::FETCH_ASSOC);
    }

    public function fetchColumn(string $sql,array $params = [], int $column = 0)
    {
        return $this->query($sql, $params)->fetchColumn($column);
    }
}
