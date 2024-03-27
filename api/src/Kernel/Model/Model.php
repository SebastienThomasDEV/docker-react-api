<?php

namespace Api\Framework\Kernel\Model;

use Api\Framework\Kernel\Exception\ExceptionManager;
use Api\Framework\Kernel\Http\JsonResponse;
use \PDO;

class Model
{
    private static ?Model $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        try {
            $this->pdo = new PDO("mysql:dbname=$db;host=$host:$port", $user, $pass);
            $this->pdo->exec("SET CHARACTER SET utf8");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            ExceptionManager::send($e);
        }
    }

    public final static function getInstance(): Model
    {
        if (self::$instance === null) {
            self::$instance = new Model;
        }
        return self::$instance;
    }

    public final function getTables(): array
    {
        $query = $this->pdo->prepare("SHOW TABLES");
        $query->execute();
        $untreatedTables = $query->fetchAll(PDO::FETCH_ASSOC);
        $tables = [];
        foreach ($untreatedTables as $table) {
            $tableName = $table['Tables_in_' . $_ENV['DB_DATABASE']];
            $table = explode('_', $table['Tables_in_' . $_ENV['DB_DATABASE']]);
            if (count($table) > 1) {
                foreach ($table as $key => $value) {
                    if ($key > 0) $table[$key] = ucfirst($value);
                }
                $table = implode('', $table);
            } else {
                $table = $table[0];
            }
            $tables[$table] = $tableName;
        }
        return $tables;
    }

    public final function get(string $table, int $id): array | object
    {
        $table = $this->getTables()[$table];
        try {
            $query = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id LIMIT 1");
            $query->execute(['id' => $id]);
            return $query->fetchAll(PDO::FETCH_ASSOC)[0] ?? new JsonResponse(['message' => 'Resource not found for id=' . $id . " in table " . $table], 404);
        } catch (\PDOException $e) {
           return ExceptionManager::send($e);
        }
    }

    public final function getAll(string $table): array | object
    {
        $table = $this->getTables()[$table];
        try {
            $query = $this->pdo->prepare("SELECT * FROM $table");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC) ?? new JsonResponse(['message' => 'Resource not found in table ' . $table], 404);
        } catch (\PDOException $e) {
            return ExceptionManager::send($e);
        }
    }

    public final function post(string $table, array $data): void
    {
        $table = $this->getTables()[$table];
        try {
            $columns = implode(', ', array_keys($data));
            $values = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));
            $query = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($values)");
            $query->execute($data);
        } catch (\PDOException $e) {
            ExceptionManager::send($e);
        }

    }

    public final function put(string $table, int $id, array $data): void
    {
        $table = $this->getTables()[$table];
        try {
            $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
            $query = $this->pdo->prepare("UPDATE $table SET $set WHERE id = :id");
            $query->execute(array_merge($data, ['id' => $id]));
        } catch (\PDOException $e) {
            ExceptionManager::send($e);
        }
    }

    public final function delete(string $table, int $id): void
    {
        $table = $this->getTables()[$table];
        try {
            $query = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
            $query->execute(['id' => $id]);
        } catch (\PDOException $e) {
            ExceptionManager::send($e);
        }
    }

    public final function patch(string $table, int $id, array $data): void
    {
        $table = $this->getTables()[$table];
        try {
            $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
            $query = $this->pdo->prepare("UPDATE $table SET $set WHERE id = :id");
            $query->execute(array_merge($data, ['id' => $id]));
        } catch (\PDOException $e) {
            ExceptionManager::send($e);
        }
    }

    public final function query(string $query, array $data = null): array | object
    {
        try {
            $query = $this->pdo->prepare($query);
            $query->execute($data);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return ExceptionManager::send($e);
        }
    }


}
