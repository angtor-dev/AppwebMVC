<?php
require_once "Models/Database.php";

class Model
{
    protected Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function listar() : array
    {
        $table = get_class($this);
        $query = "SELECT * FROM $table";
        
        return $this->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query(String $query) : PDOStatement {
        return $this->db->connect()->query($query);
    }

    protected function prepare(String $query) : PDOStatement {
        return $this->db->connect()->prepare($query);
    }
}
?>