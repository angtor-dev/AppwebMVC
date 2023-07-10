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

    public static function cargar(int $id) : null|object
    {
        $bd = new Database();
        $table = static::class;
        $query = "SELECT * FROM $table WHERE id = $id";

        $stmt = $bd->connect()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetch();
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