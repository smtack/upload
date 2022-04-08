<?php
class Database {
  private static $instance = null;

  public $pdo;

  private $query;
  private $error = false;
  private $results;
  private $count = 0;
  private $dsn;
  private $options;

  private function __construct() {
    $this->dsn = "mysql:host=" . Config::get('mysql/dbhost') . ";dbname=" . Config::get('mysql/dbname') . ";charset=" . Config::get('mysql/dbchar');

    $this->options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_EMULATE_PREPARES => false
    ];

    try {
      $this->pdo = new PDO($this->dsn, Config::get('mysql/dbuser'), Config::get('mysql/dbpass'), $this->options);
    } catch(\PDOException $e) {
      throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
  }

  public static function getInstance() {
    if(!isset(self::$instance)) {
      self::$instance = new Database();
    }

    return self::$instance;
  }

  public function query($sql, $params = array()) {
    $this->error = false;

    if($this->query = $this->pdo->prepare($sql)) {
      $i = 1;

      if(count($params)) {
        foreach($params as $param) {
          $this->query->bindValue($i, $param);
          $i++;
        }
      }

      if($this->query->execute()) {
        $this->results = $this->query->fetchAll();
        $this->count = $this->query->rowCount();
      } else {
        $this->error = true;
      }
    }

    return $this;
  }

  public function insert($table, $fields = array()) {
    $keys = array_keys($fields);
    $values = '';
    $i = 1;

    foreach($fields as $field) {
      $values .= '?';

      if($i < count($fields)) {
        $values .= ', ';
      }

      $i++;
    }

    $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

    if(!$this->query($sql, $fields)->error()) {
      return true;
    }

    return false;
  }

  public function select($table, $join = array(), $where = array(), $order = null) {
    if(count($join) == 3) {
      $table_to_join = $join[0];
      $field1 = $join[1];
      $field2 = $join[2];

      if(count($where) == 3) {
        $operators = array('=', '>', '<', '>=', '<=', 'LIKE');
  
        $field = $where[0];
        $operator = $where[1];
        $value = $where[2];
  
        if(in_array($operator, $operators)) {
          if(isset($order)) {
            $sql = "SELECT * FROM {$table} LEFT JOIN {$table_to_join} ON {$field1} = {$field2} WHERE {$field} {$operator} ? ORDER BY {$order} DESC";

            if(!$this->query($sql, array($value))->error()) {
              return $this;
            }
          } else {
            $sql = "SELECT * FROM {$table} LEFT JOIN {$table_to_join} ON {$field1} = {$field2} WHERE {$field} {$operator} ?";

            if(!$this->query($sql, array($value))->error()) {
              return $this;
            }
          }
        }
      } else {
        if(isset($order)) {
          $sql = "SELECT * FROM {$table} LEFT JOIN {$table_to_join} ON {$field1} = {$field2} ORDER BY {$order} DESC";

          if(!$this->query($sql)->error()) {
            return $this;
          }
        } else {
          $sql = "SELECT * FROM {$table} LEFT JOIN {$table_to_join} ON {$field1} = {$field2}";

          if(!$this->query($sql)->error()) {
            return $this;
          }
        }
      }
    } else {
      if(count($where) == 3) {
        $operators = array('=', '>', '<', '>=', '<=', 'LIKE');
  
        $field = $where[0];
        $operator = $where[1];
        $value = $where[2];
  
        if(in_array($operator, $operators)) {
          if(isset($order)) {
            $sql = "SELECT * FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$order} DESC";

            if(!$this->query($sql, array($value))->error()) {
              return $this;
            }
          } else {
            $sql = "SELECT * FROM {$table} WHERE {$field} {$operator} ?";

            if(!$this->query($sql, array($value))->error()) {
              return $this;
            }
          }
        }
      } else {
        if(isset($order)) {
          $sql = "SELECT * FROM {$table} ORDER BY {$order} DESC";

          if(!$this->query($sql)->error()) {
            return $this;
          }
        } else {
          $sql = "SELECT * FROM {$table}";

          if(!$this->query($sql)->error()) {
            return $this;
          }
        }
      }
    }

    return false;
  }

  public function update($table, $id_field, $id, $fields) {
    $set = '';
    $i = 1;

    foreach($fields as $name => $value) {
      $set .= "{$name} = ?";

      if($i < count($fields)) {
        $set .= ', ';
      }

      $i++;
    }

    $sql = "UPDATE {$table} SET {$set} WHERE {$id_field} = {$id}";

    if(!$this->query($sql, $fields)->error()) {
      return true;
    }

    return false;
  }

  public function delete($table, $where = array()) {
    if(count($where) == 3) {
      $operators = array('=', '>', '<', '>=', '<=');

      $field = $where[0];
      $operator = $where[1];
      $value = $where[2];

      if(in_array($operator, $operators)) {
        $sql = "DELETE FROM {$table} WHERE {$field} {$operator} ?";

        if(!$this->query($sql, array($value))->error()) {
          return $this;
        }
      }
    }

    return false;
  }

  public function exists($table, $arr) {
    $res = $this->select($table, array(), $arr);

    return ($res->count()) ? true : false;
  }

  public function results() {
    return $this->results;
  }

  public function first() {
    return $this->results()[0];
  }

  public function error() {
    return $this->error;
  }

  public function count() {
    return $this->count;
  }
}