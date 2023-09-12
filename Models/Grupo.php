<?php
require_once "Models/Model.php";
require_once "Models/Grupo.php";
require_once "Models/Enums/EstadosGrupo.php";

class Grupo extends Model
{
    public int $id;
    public int $idNivelCrecimiento;
    public int $idProfesor;
    public string $nombre;
    public int $estado;
    public int $estatus;
    
    public NivelCrecimiento $nivelCrecimiento;
    public Usuario $profesor;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idNivelCrecimiento)) {
            $this->nivelCrecimiento = NivelCrecimiento::cargar($this->idNivelCrecimiento);
        }
        if (!empty($this->idProfesor)) {
            $this->profesor = Usuario::cargar($this->idProfesor);
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO grupo(idNivelCrecimiento, idProfesor, nombre) VALUES(:idNivelCrecimiento, :idProfesor, :nombre)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idNivelCrecimiento", $this->idNivelCrecimiento);
            $stmt->bindValue("idProfesor", $this->idProfesor);
            $stmt->bindValue("nombre", $this->nombre);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al crear el grupo.";
            throw $th;
        }
    }

    public function actualizar() : void
    {
        $query = "UPDATE grupo SET idNivelCrecimiento = :idNivelCrecimiento, idProfesor = :idProfesor, nombre = :nombre WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idNivelCrecimiento", $this->idNivelCrecimiento);
            $stmt->bindValue("idProfesor", $this->idProfesor);
            $stmt->bindValue("nombre", $this->nombre);
            $stmt->bindValue("id", $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar el grupo.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        $errores = 0;
        if (empty($this->idNivelCrecimiento)) {
            $_SESSION['errores'][] = "Debe seleccionar un nivel de crecimiento.";
            $errores++;
        }
        if (empty($this->idProfesor)) {
            $_SESSION['errores'][] = "Se debe especificar un profesor.";
            $errores++;
        }
        if (preg_match(REG_ALFANUMERICO, $this->nombre)) {
            $_SESSION['errores'][] = "El nombre solo puede contener caracteres alfanúmericos.";
            $errores++;
        }
        if (empty($this->estado) || is_null(EstadosGrupo::tryFrom($this->estado))) {
            $_SESSION['errores'][] = "El estado ingresado es invalido.";
            $errores++;
        }

        if ($errores > 0) {
            return false;
        }
        return true;
    }
}
?>