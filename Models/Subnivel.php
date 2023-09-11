<?php
require_once "Models/Model.php";
require_once "Models/NivelCrecimiento.php";

class Subnivel extends Model
{
    public int $id;
    public int $idNivelCrecimiento;
    public string $nombre;
    public int $nivel;
    public int $estatus;
}
?>