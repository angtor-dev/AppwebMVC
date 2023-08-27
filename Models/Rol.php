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
}
?>