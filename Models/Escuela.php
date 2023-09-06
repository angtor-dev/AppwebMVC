<?php
require_once "Models/Model.php";

class Escuela extends Model
{
    public int $id;
    public int $idSede;
    
    public Sede $sede;
}
?>