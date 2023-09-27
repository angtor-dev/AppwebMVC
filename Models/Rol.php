<?php
require_once "Models/Model.php";
require_once "Models/Permiso.php";

class Rol extends Model
{
    public int $id;
    private string $nombre;
    private ?string $descripcion;
    private int $nivel;

    /** @var Permiso[] */
    public array $permisos = array();

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->id)) {
            $this->permisos = Permiso::cargarRelaciones($this->id, get_class());
        }
    }

    public function registrar() : void
    {
        $sql = "INSERT INTO permisos() VALUES()";
            
        try {
            $this->db->pdo()->beginTransaction();

            // Primero crea permisos del rol
            $this->query($sql);

            $idPermisos = $this->db->pdo()->lastInsertId();

            // Registra el rol
            $sql = "INSERT INTO rol(nombre, descripcion, nivel, idPermisos)
                VALUES(:nombre, :descripcion, :nivel, :idPermisos)";

            $stmt = $this->prepare($sql);
            $stmt->bindValue('nombre', $this->nombre);
            $stmt->bindValue('descripcion', $this->descripcion);
            $stmt->bindValue('nivel', $this->nivel);
            $stmt->bindValue('idPermisos', $idPermisos);

            $stmt->execute();

            // Guarda los cambios
            $this->db->pdo()->commit();

        } catch (\Throwable $th) {
            // Revierte los cambios en la bd
            if ($this->db->pdo()->inTransaction()) {
                $this->db->pdo()->rollBack();
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar el rol.";
            throw $th;
        }
    }

    public function actualizar() : void
    {
        $sql = "UPDATE rol SET nombre = :nombre, descripcion = :descripcion, nivel = :nivel WHERE id = :id";

        try {
            $stmt = $this->prepare($sql);
            $stmt->bindValue('nombre', $this->nombre);
            $stmt->bindValue('descripcion', $this->descripcion);
            $stmt->bindValue('nivel', $this->nivel);
            $stmt->bindValue('id', $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar el rol.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        if (empty($this->nombre)) {
            $_SESSION['errores'][] = "Se debe proporcionar un nombre para el rol a registrar.";
            return false;
        }
        if (empty($this->nivel) && $this->nivel !== 0) {
            $_SESSION['errores'][] = "Se debe especificar un nivel de rol.";
            return false;
        }

        return true;
    }

    public function tienePermiso(string $modulo, string $permiso) : bool
    {
        $permiso = "get".$permiso;
        
        foreach ($this->permisos as $p) {
            if ($p->modulo->getNombre() == $modulo && $p->$permiso()) {
                return true;
            }
        }
        return false;
    }

    /** Mapea los valores de un formulario post a las propiedades del objeto */
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

    // Getters
    public function getNombre() : string {
        return $this->nombre;
    }
    public function getDescripcion() : ?string {
        return $this->descripcion;
    }
    public function getNivel() : int {
        return $this->nivel;
    }
}
?>