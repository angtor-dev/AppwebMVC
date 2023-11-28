<?php
require_once "Models/Model.php";

class Evento extends Model
{
    public int $id;
    private string $titulo;
    private string $descripcion;
    private string $fechaInicio;
    private string $fechaFinal;
    private string $color;

 
}
?>