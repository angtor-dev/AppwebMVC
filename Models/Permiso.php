<?php
require_once "Models/Model.php";
require_once "Models/Rol.php";
require_once "Models/Modulo.php";

class Permiso extends Model
{
    public int $id;
    private int $idRol;
    private int $idModulo;
    private bool $consultar = false;
    private bool $registrar = false;
    private bool $actualizar = false;
    private bool $eliminar = false;

    public Modulo $modulo;

    public function __construct()
    {
        if (!empty($this->idModulo)) {
            $this->modulo = Modulo::cargar($this->idModulo);
        }
    }

    public function actualizar() : void
    {
        $query = "UPDATE permiso
            SET consultar = :consultar, registrar = :registrar, actualizar = :actualizar, eliminar = :eliminar
            WHERE id = $this->id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('consultar', $this->consultar);
            $stmt->bindValue('registrar', $this->registrar);
            $stmt->bindValue('actualizar', $this->actualizar);
            $stmt->bindValue('eliminar', $this->eliminar);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) echo $th->getMessage();
            die;
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar los permisos de rol.";
            throw $th;
        }
    }

    // Getters
    public function getConsultar() : bool {
        return $this->consultar;
    }
    public function getActualizar() : bool {
        return $this->actualizar;
    }
    public function getRegistrar() : bool {
        return $this->registrar;
    }
    public function getEliminar() : bool {
        return $this->eliminar;
    }
}
?>