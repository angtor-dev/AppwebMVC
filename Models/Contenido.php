<?php
require_once "Models/Model.php";
require_once "Models/Clase.php";

class Contenido extends Model
{
    public int $id;
    private int $idClase;
    private string $titulo;
    private string $contenido;
    private int $estatus;

    public function registrar() : void
    {
        $query = "INSERT INTO contenido(idClase, titulo, contenido) VALUES(:idClase, :titulo, :contenido)";

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

    public function actualizar() : void
    {
        $query = "UPDATE contenido SET titulo = :titulo, contenido = :contenido WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("titulo", $this->titulo);
            $stmt->bindValue("contenido", $this->contenido);
            $stmt->bindValue("id", $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar el contenido.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        $errores = 0;
        if (!preg_match(REG_ALFANUMERICO, $this->titulo)) {
            $_SESSION['errores'][] = "El titulo solo puede contener valores alfanuméricos";
            $errores++;
        }

        if ($errores > 0) {
            return false;
        }
        return true;
    }

    /** Mapea los valores de un formulario post a las propiedades del objeto */
    public function mapFromPost() : bool
    {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = trim($value);
                }
            }
            return true;
        }
        return false;
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
    public function getEstatus() : int {
        return $this->estatus;
    }
}
?>