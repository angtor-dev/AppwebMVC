<?php
require_once "Models/Model.php";
require_once "Models/Usuario.php";

class Territorio extends Model
{
    public int $id;
    public int $idSede;
    public int $idLider;
    public string $codigo;
    public string $nombre;
    public string $detalles;

    public Sede $sede;
    public Usuario $lider;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idSede)) {
            $this->sede = Usuario::cargar($this->idSede);
        }
        if (!empty($this->idLider)) {
            $this->lider = Usuario::cargar($this->idLider);
        }
    }
}
?>