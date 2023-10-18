<?php
require_once "Models/Model.php";
require_once "Models/Clase.php";
require_once "Models/Matricula.php";

class Nota extends Model
{
    public int $id;
    private int $idClase;
    private int $idEstudiante;
    private float $calificacion;
    
    public Clase $clase;
    public Usuario $estudiante;
    
    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idClase)) {
            $this->clase = Clase::cargar($this->idClase);
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO nota(idClase, idEstudiante, calificacion)
            VALUES(:idClase, :idEstudiante, :calificacion)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idClase", $this->idClase);
            $stmt->bindValue("idEstudiante", $this->idEstudiante);
            $stmt->bindValue("calificacion", $this->calificacion);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar la nota.";
            throw $th;
        }
    }

    public static function buscarNota(int $idClase, int $idEstudiante) : ?Nota
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM nota WHERE idClase = $idClase AND idEstudiante = $idEstudiante LIMIT 1";

        $stmt = $db->pdo()->query($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Nota");

        $nota = $stmt->fetch();
        
        if ($nota == false) {
            return null;
        }

        return $nota;
    }

    // Getters
    public function getIdClase() : int {
        return $this->idClase;
    }
    public function getIdEstudiante() : int {
        return $this->idEstudiante;
    }
    public function getCalificaion() : float {
        return $this->calificacion;
    }
}
?>