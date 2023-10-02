<?php
require_once "Models/Model.php";
require_once "Models/NivelCrecimiento.php";

class Subnivel extends Model
{
    public int $id;
    private int $idNivelCrecimiento;
    private string $nombre;
    private int $nivel;
    private int $estatus;

    public NivelCrecimiento $nivelCrecimiento;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idNivelCrecimiento)) {
            $this->nivelCrecimiento = NivelCrecimiento::cargar($this->idNivelCrecimiento);
        }
    }

    // Getters
    public function getNombre() : string {
        return $this->nombre ?? "";
    }
    public function getNivel() : int {
        return $this->nivel ?? 0;
    }
}
?>