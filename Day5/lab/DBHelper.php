<?php

require_once "db.php";

trait DBHelper {
    protected $db;

    public function initDB() {
        if ($this->db === null) {
            $this->db = DB::getInstance("php_tanta", "localhost", "root", "");
        }
        return $this->db;
    }
}
