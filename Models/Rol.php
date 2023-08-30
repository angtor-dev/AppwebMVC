<?php
require_once "Models/Model.php";
require_once "Models/Permisos.php";

class Rol extends Model
{
    public int $id;
    public int $idPermisos;
    public string $nombre;
    public ?string $descripcion;
    public int $nivel;

    public Permisos $permisos;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idPermisos)) {
            $this->permisos = Permisos::cargar($this->idPermisos);
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

    public function esValido() : bool
    {
        if (empty($this->nombre)) {
            $_SESSION['errores'][] = "Se debe proporcionar un nombre para el rol a registrar.";
            return false;
        }
        if (empty($this->nivel)) {
            $_SESSION['errores'][] = "Se debe especificar un nivel de rol.";
            return false;
        }

        return true;
    }
}
?>