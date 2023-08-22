<?php
require_once "Models/Database.php";

abstract class Model
{
    protected Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public static function listar() : array
    {
        $bd = Database::getInstance();
        $table = static::class;
        $query = "SELECT * FROM $table";

        $stmt = $bd->pdo()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);
        
        return $stmt->fetchAll();
    }

    public static function cargar(int $id) : null|object
    {
        $bd = Database::getInstance();
        $table = static::class;
        $query = "SELECT * FROM $table WHERE id = $id";

        $stmt = $bd->pdo()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetch();
    }

    public static function cargarRelaciones(int $id, string $tablaForanea) : null|array
    {
        $bd = Database::getInstance();
        $table = static::class;
        $query = "SELECT * FROM $table WHERE id$tablaForanea = $id";

        $stmt = $bd->pdo()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetchAll();
    }

    public function mapFromPost() : bool
    {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            return true;
        }
        return false;
    }

    protected function query(String $query) : PDOStatement {
        return $this->db->pdo()->query($query);
    }

    protected function prepare(String $query) : PDOStatement {
        return $this->db->pdo()->prepare($query);
    }
}
?>