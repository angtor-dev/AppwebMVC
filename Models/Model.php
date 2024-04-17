<?php
require_once "Models/Database.php";

abstract class Model
{
    public int $id;
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retorna un array de objetos del modelo que lo instacía
     *
     * @param int|null $estatus Si no se especifica, retorna todas las filas de la tabla.
     * Si se especifica, retorna las filas donde el estatus sea igual al indicado.
     * @return array<Model>
     **/
    public static function listar(int $estatus = null): array
    {
        $bd = Database::getInstance();
        $table = strtolower(static::class);
        $query = "SELECT * FROM $table" . (isset($estatus) ? " WHERE estatus = $estatus" : "");

        $stmt = $bd->pdo()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);

        return $stmt->fetchAll();
    }

    /**
     * Retorna una instacia del modelo actual con un id especifico
     * 
     * @param int $id El id a buscar en la bd
     * @return null|Model El modelo encontrado o null en caso de no haber coincidencias
     */
    public static function cargar(int $id): null|Model
    {
        $bd = Database::getInstance();
        $table = strtolower(static::class);
        $query = "SELECT * FROM $table WHERE id = $id";

        $stmt = $bd->pdo()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetch();
    }

    /**
     * Retorna un array de objetos del modelo que lo instacía donde el id de la tabla
     * foranea coincida con el id del modelo
     * 
     * @param int $id El id del modelo actual
     * @param string $tablaForanea El nombre de la tabla con la que se relaciona el modelo
     * @param int|null $estatus Si se especifica, retorna las filas donde el estatus sea igual al indicado.
     * @return array<Model>
     */
    public static function cargarRelaciones(int $id, string $tablaForanea, int $estatus = null): array
    {
        $bd = Database::getInstance();
        $table = strtolower(static::class);
        $query = "SELECT * FROM $table WHERE id$tablaForanea = $id" . (isset($estatus) ? " AND estatus = $estatus" : "");

        $stmt = $bd->pdo()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);

        if ($stmt->rowCount() == 0) {
            return array();
        }
        return $stmt->fetchAll();
    }

    /**
     * Retorna un array de objetos del modelo que lo instacía donde el id de la tabla
     * foranea coincida con el id del modelo en una relacion de muchos a muchos
     * 
     * @param int $id El id del modelo actual
     * @param string $tablaForanea el nombre de la tabla con la que se relaciona el modelo
     * @param string $tablaIntermediaria El nombre de la tabla intermediaria entre las relaciones
     * @return array<Model>
     **/
    public static function cargarMultiplesRelaciones(
        int $id,
        string $tablaForanea,
        string $tablaIntermediaria
    ): array {
        $bd = Database::getInstance();
        $table = strtolower(static::class);
        $tablaIntermediaria = strtolower($tablaIntermediaria);
        $query = "SELECT t.* FROM $table AS t
            INNER JOIN $tablaIntermediaria AS ti ON t.id = ti.id$table
            WHERE ti.id$tablaForanea = $id";

        // echo $query; exit();
        $stmt = $bd->pdo()->query($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $table);

        if ($stmt->rowCount() == 0) {
            return array();
        }
        return $stmt->fetchAll();
    }

    /**
     * Elimina u oculta la instancia actual en la BD
     * 
     * @param bool $eliminadoLogico Si es true oculta la instancia en la BD (UPDATE estatus = 0),
     * caso contrario elimina la instancia de la BD (DELETE FROM)
     */
    public function eliminar(bool $eliminadoLogico = true): bool
    {
        $tabla = strtolower(get_class($this));
        $query = $eliminadoLogico
            ? "UPDATE $tabla set estatus = 0 WHERE id = :id"
            : "DELETE FROM $tabla WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('id', $this->id);

            $stmt->execute();

            return true;
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE)
                var_dump($th); // Eliminar esto al crear vista para errores
            $_SESSION['errores'][] = "Ha ocurrido un error al eliminar $tabla.";
            throw $th;
        }
    }

    /** Mapea los valores de un formulario post a las propiedades del objeto */
    public function mapFromPost(): bool
    {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = trim($value);
                }
            }
            return true;
        }
        return false;
    }

    /** Shorthand para PDO::query() */
    protected function query(string $query): PDOStatement
    {
        return $this->db->pdo()->query($query);
    }

    /** Shorthand para PDO::prepare() */
    protected function prepare(string $query): PDOStatement
    {
        return $this->db->pdo()->prepare($query);
    }
}
?>