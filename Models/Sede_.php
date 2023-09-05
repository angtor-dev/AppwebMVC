<?php
require_once "Models/Model.php";
require_once "Models/Usuario.php";

class Sede extends Model
{
    public int $id;
    public ?int $idPastor;
    public string $pais;
    public string $codigo;
    public string $direccion;

    public ?Usuario $pastor;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idPastor)) {
            $this->pastor = Usuario::cargar($this->idPastor);
        }
    }
}
?>