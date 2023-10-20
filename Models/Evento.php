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
            throw $th;
        }
    }

    // Getters
    public function getTitulo() : string {
        return $this->titulo;
    }
    public function getDescripcion() : string {
        return $this->descripcion;
    }
    public function getFechaInicio() : string {
        return $this->fechaInicio;
    }
    public function getFechaFinal() : string {
        return $this->fechaFinal;
    }
    public function getColor() : string {
        return $this->color;
    }
    
    // Setters
    public function setTitulo($titulo) : void {
        $this->titulo = $titulo;
    }
    public function setDescripcion($descripcion) : void {
        $this->descripcion = $descripcion;
    }
    public function setFechaInicio($fechaInicio) : void {
        $this->fechaInicio = $fechaInicio;
    }
    public function setFechaFinal($fechaFinal) : void {
        $this->fechaFinal = $fechaFinal;
    }
    public function setColor($color) : void {
        $this->color = $color;
    }
}
?>