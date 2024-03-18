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

    public final function get(string $table, int $id): array | object
    {
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
        try {
            $query = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
            $query->execute(['id' => $id]);
        } catch (\PDOException $e) {
            ExceptionManager::send($e);
        }
    }

    public final function patch(string $table, int $id, array $data): void
    {
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
