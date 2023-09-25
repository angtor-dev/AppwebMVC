<?php
require_once "Models/Model.php";

class Modulo extends Model
{
    public int $id;
    private string $nombre;

    // Override para impedir eliminar
    public function eliminar(bool $eliminadoLogico = true) : void
    {
        $_SESSION['errores'][] = "No se pueden eliminar registros de la tabla modulos.";
        return;
    }

    /** Recupera la instacia con el nombre especificado */
    public static function tryFromNombre(string $nombre) : ?Modulo
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM modulo WHERE nombre = :nombre LIMIT 1";

        $stmt = $db->pdo()->prepare($sql);
        $stmt->bindValue("nombre", $nombre);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetch();
    }

    // Getters
    public function getNombre() : string {
        return $this->nombre;
    }
}
?>