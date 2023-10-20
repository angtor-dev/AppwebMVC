<?php
require_once "Models/Model.php";

class Matricula extends Model
{
    public int $id;
    private int $idGrupo;
    private int $idEstudiante;
    private int $estado;
    private string $fechaRegistro;

    public Usuario $estudiante;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idEstudiante)) {
            $this->estudiante = Usuario::cargar($this->idEstudiante);
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO matricula (idGrupo, idEstudiante, estado, fechaRegistro)
            VALUES (:idGrupo, :idEstudiante, :estado, CURDATE())";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idGrupo", $this->idGrupo);
            $stmt->bindValue("idEstudiante", $this->idEstudiante);
            $stmt->bindValue("estado", $this->estado);

            $stmt->execute();
        } catch (\Throwable $th) {
            redirigir("/AppwebMVC/Agenda");
        }
    }

    // Setters
    public function setIdGrupo(int $id) {
        $this->idGrupo = $id;
    }
    public function setIdEstudiante(int $id) {
        $this->idEstudiante = $id;
    }
    public function setEstado(int $estado) {
        $this->estado = $estado;
    }

    // Getters
    public function getIdGrupo() : int {
        return $this->idGrupo;
    }
    public function getIdEstudiante() : int {
        return $this->idEstudiante;
    }
    public function getEstado() : int {
        return $this->estado;
    }
    public function getFechaRegistro() : string {
        return $this->fechaRegistro;
    }

    // Estadisticas
    public static function inscripcionesPorMes() : array
    {
        $db = Database::getInstance();
        $query = "SELECT 
                SUM(CASE WHEN MONTH(fechaRegistro) = 1 THEN 1 ELSE 0 END) AS Enero,
                SUM(CASE WHEN MONTH(fechaRegistro) = 2 THEN 1 ELSE 0 END) AS Febrero,
                SUM(CASE WHEN MONTH(fechaRegistro) = 3 THEN 1 ELSE 0 END) AS Marzo,
                SUM(CASE WHEN MONTH(fechaRegistro) = 4 THEN 1 ELSE 0 END) AS Abril,
                SUM(CASE WHEN MONTH(fechaRegistro) = 5 THEN 1 ELSE 0 END) AS Mayo,
                SUM(CASE WHEN MONTH(fechaRegistro) = 6 THEN 1 ELSE 0 END) AS Junio,
                SUM(CASE WHEN MONTH(fechaRegistro) = 7 THEN 1 ELSE 0 END) AS Julio,
                SUM(CASE WHEN MONTH(fechaRegistro) = 8 THEN 1 ELSE 0 END) AS Agosto,
                SUM(CASE WHEN MONTH(fechaRegistro) = 9 THEN 1 ELSE 0 END) AS Septiembre,
                SUM(CASE WHEN MONTH(fechaRegistro) = 10 THEN 1 ELSE 0 END) AS Octubre,
                SUM(CASE WHEN MONTH(fechaRegistro) = 11 THEN 1 ELSE 0 END) AS Noviembre,
                SUM(CASE WHEN MONTH(fechaRegistro) = 12 THEN 1 ELSE 0 END) AS Diciembre
            FROM matricula WHERE YEAR(fechaRegistro) = YEAR(CURDATE())";
        
        try {
            $stmt = $db->pdo()->query($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                die($th->getMessage());
            }
            $_SESSION['erorres'][] = "Ah ocurrido un error al listar las estadisticas.";
            throw $th;
        }
    }
}
?>