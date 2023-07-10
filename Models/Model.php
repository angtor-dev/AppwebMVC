<?php
require_once "Models/Database.php";

class Model
{
    protected Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    public static function listar() : array
    {
        $bd = new Database();
        $table = static::class;
        $query = "SELECT * FROM $table";
        
        $stmt = $bd->connect()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);
        
        return $stmt->fetchAll();
    }
    }

    protected function query(String $query) : PDOStatement {
        return $this->db->connect()->query($query);
    }

    protected function prepare(String $query) : PDOStatement {
        return $this->db->connect()->prepare($query);
    }
}
?>