<?php

class DB {
    private $dbname;
    private $host;
    private $user;
    private $pass;
    private $pdo_connection;

    private static $instance = null;

    private function __construct($dbname, $host, $user, $pass) {
        $this->dbname = $dbname;
        $this->host   = $host;
        $this->user   = $user;
        $this->pass   = $pass;

        try {
            $this->pdo_connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->user,
                $this->pass,
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /// Get single instance
    public static function getInstance($dbname, $host, $user, $pass) {
        if (self::$instance === null) {
            self::$instance = new DB($dbname, $host, $user, $pass);
        }
        return self::$instance;
    }

    function get_connection() {
        return $this->pdo_connection;
    }

    /// Select all rows (with prepare)
    function getAll($table) {
        $sql = "SELECT * FROM `$table`";
        $stmt = $this->pdo_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /// Select with condition
    function select($table, $conditions = [], $fields = "*") {
        $sql = "SELECT $fields FROM `$table`";
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $col => $val) {
                $where[] = "`$col` = :$col";
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = $this->pdo_connection->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /// Insert data
    function insert($table, $data) {
        $columns = implode("`, `", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO `$table` (`$columns`) VALUES ($placeholders)";
        $stmt = $this->pdo_connection->prepare($sql);
        return $stmt->execute($data);
    }

    /// Update data
    function update($table, $data, $conditions) {
        $set = [];
        foreach ($data as $col => $val) {
            $set[] = "`$col` = :set_$col";
        }
        $where = [];
        foreach ($conditions as $col => $val) {
            $where[] = "`$col` = :where_$col";
        }

        $sql = "UPDATE `$table` SET " . implode(", ", $set) . " WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo_connection->prepare($sql);

        // Merge data with prefixed keys
        $params = [];
        foreach ($data as $col => $val) {
            $params["set_$col"] = $val;
        }
        foreach ($conditions as $col => $val) {
            $params["where_$col"] = $val;
        }

        return $stmt->execute($params);
    }

    /// Delete data
    function delete($table, $conditions) {
        $where = [];
        foreach ($conditions as $col => $val) {
            $where[] = "`$col` = :$col";
        }
        $sql = "DELETE FROM `$table` WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo_connection->prepare($sql);
        return $stmt->execute($conditions);
    }

    /// Custom query with prepared statements
    function query($sql, $params = []) {
        $stmt = $this->pdo_connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /// Prevent cloning
    private function __clone() {}

    /// Prevent unserializing
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}
