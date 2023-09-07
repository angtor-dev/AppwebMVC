<?php
require_once "Models/Model.php";

class NivelCrecimiento extends Model
{
    public int $id;
    public int $idEscuela;
    public string $nombre;
    public int $nivel;

    public Escuela $escuela;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idEscuela)) {
            $this->escuela = Escuela::cargar($this->idEscuela);
        }
    }

    public static function crearIniciales() : void
    {
        // Crear niveles de crecimiento iniciales
    }
}
?>