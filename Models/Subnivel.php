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
}
?>