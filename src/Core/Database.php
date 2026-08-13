<?php

namespace Core;

use PDO;

class Database
{
    private static $instance = null;

    public $pdo;

    private $results;
    private $count = 0;
    private $dsn;
    private $options;

    private function __construct()
    {
        $this->dsn = "mysql:host=" . Config::get('mysql/dbhost') . ";dbname=" . Config::get('mysql/dbname') . ";charset=" . Config::get('mysql/dbchar');

        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $this->pdo = new PDO($this->dsn, Config::get('mysql/dbuser'), Config::get('mysql/dbpass'), $this->options);
    }

    public static function getInstance(): self
    {
        if(!isset(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function query(string $sql, array $params = []): self
    {
        $statement = $this->pdo->prepare($sql);

        $i = 1;

        foreach ($params as $value) {
            $statement->bindValue($i, $value);
            $i++;
        }

        $statement->execute();

        $this->results = $statement->fetchAll();
        $this->count = count($this->results);

        return $this;
    }

    public function insert(string $table, array $fields): bool
    {
        $keys = '`' . implode('`, `', array_keys($fields)) . '`';
        $values = implode(', ', array_fill(0, count($fields), '?'));

        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$values})";

        $this->query($sql, $fields);

        return true;
    }

    public function select(string $table, array $where = [])
    {
        $sql = "SELECT * FROM {$table}";
        $params = [];

        if ($where) {
            $conditions = [];

            foreach ($where as $key => $value) {
                $conditions[] = "`{$key}` = ?";
                $params[] = $value;
            }

            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        return $this->query($sql, $params);
    }

    public function update(string $table, array $data, string $field, string|int $id): bool
    {
        $set = '';
        $i = 1;

        foreach($data as $key => $value) {
            $set .= "`{$key}` = ?";

            if ($i < count($data)) {
                $set .= ', ';
            }

            $i++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE `{$field}` = ?";

        $params = array_values($data);
        $params[] = $id;

        $this->query($sql, $params);

        return true;
    }

    public function delete(string $table, array $where): bool
    {
        $conditions = [];
        $params = [];

        foreach ($where as $key => $value) {
            $conditions[] = "`{$key}` = ?";
            $params[] = $value;
        }

        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $conditions);

        $this->query($sql, $params);

        return true;
    }

    public function exists(string $table, array $where): bool
    {
        $this->select($table, $where);

        return $this->count() > 0;
    }

    public function results()
    {
        return $this->results;
    }

    public function first()
    {
        return $this->results[0] ?? null;
    }

    public function count()
    {
        return $this->count;
    }
}
