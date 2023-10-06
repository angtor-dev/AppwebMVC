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
}
?>