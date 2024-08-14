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

    public function __construct($idRol = null, $idModulo = null, $consultar = false, $registrar = false, $actualizar = false, $eliminar = false)
    {
        parent::__construct();
        if (!empty($idRol) && !empty($idModulo)) {
            $this->idRol = $idRol;
            $this->idModulo = $idModulo;
            $this->consultar = $consultar;
            $this->registrar = $registrar;
            $this->actualizar = $actualizar;
            $this->eliminar = $eliminar;
        }
        if (!empty($this->idModulo)) {
            $this->modulo = Modulo::cargar($this->idModulo);
        }
    }

    public function mapFromPost() : bool
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

    public function registrar() 
    {
        $query = "INSERT INTO permiso(idRol, idModulo, consultar, registrar, actualizar, eliminar)
            VALUES(:idRol, :idModulo, :consultar, :registrar, :actualizar, :eliminar)";
        
        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idRol", $this->idRol);
            $stmt->bindValue("idModulo", $this->idModulo);
            $stmt->bindValue("consultar", $this->consultar, PDO::PARAM_BOOL);
            $stmt->bindValue("registrar", $this->registrar, PDO::PARAM_BOOL);
            $stmt->bindValue("actualizar", $this->actualizar, PDO::PARAM_BOOL);
            $stmt->bindValue("eliminar", $this->eliminar, PDO::PARAM_BOOL);

            $stmt->execute();

            return true;
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar los permisos de rol.";
            throw $th;
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

    // Setters
    public function setAllFalse() : void
    {
        $this->consultar = false;
        $this->registrar = false;
        $this->actualizar = false;
        $this->eliminar = false;
    }
    public function setPermiso(string $permiso, bool $valor = false)
    {
        $this->$permiso = $valor;
    }
}
?>