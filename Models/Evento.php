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

    public function registrar() : void
    {
        $query = "INSERT INTO evento (titulo, descripcion, fechaInicio, fechaFinal, color)
            VALUES (:titulo, :descripcion, :fechaInicio, :fechaFinal, :color)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("titulo", $this->titulo);
            $stmt->bindValue("descripcion", $this->descripcion);
            $stmt->bindValue("fechaInicio", $this->fechaInicio);
            $stmt->bindValue("fechaFinal", $this->fechaFinal);
            $stmt->bindValue("color", $this->color);

            $stmt->execute();
        } catch (\Throwable $th) {
            redirigir("/AppwebMVC/Agenda");
        }
    }
}
?>