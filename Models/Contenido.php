<?php
require_once "Models/Model.php";
require_once "Models/Clase.php";

class Contenido extends Model
{
    public int $id;
    private int $idClase;
    private string $titulo;
    private string $contenido;

    public function registrar() : void
    {
        $query = "INSERT INTO clase(idClase, titulo, contenido) VALUES(:idClase, :titulo, :contenido)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idClase", $this->idClase);
            $stmt->bindValue("titulo", $this->titulo);
            $stmt->bindValue("contenido", $this->contenido);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar el contenido.";
            throw $th;
        }
    }

    // Getters
    public function getIdClase() : int {
        return $this->idClase;
    }
    public function getTitulo() : string {
        return $this->titulo;
    }
    public function getContenido() : string {
        return $this->contenido;
    }
}
?>