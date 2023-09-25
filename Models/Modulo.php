<?php
require_once "Models/Model.php";

class Modulo extends Model
{
    public int $id;
    private string $nombre;

    // Override para impedir eliminar
    public function eliminar(bool $eliminadoLogico = true) : void
    {
        $_SESSION['errores'][] = "No se pueden eliminar registros de la tabla modulos.";
        return;
    }

    // Getters
    public function getNombre() : string {
        return $this->nombre;
    }
}
?>