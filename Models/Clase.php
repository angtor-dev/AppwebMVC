<?php
require_once "Models/Model.php";
require_once "Models/Grupo.php";
require_once "Models/Contenido.php";

class Clase extends Model
{
    public int $id;
    private int $idGrupo;
    private string $titulo;
    private ?string $objetivo;
    
    public Grupo $grupo;
    /** @var Contenido[] */
    public array $contenidos;
    
    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idGrupo)) {
            $this->grupo = Grupo::cargar($this->idGrupo);
        }
        if (!empty($this->id)) {
            $this->contenidos = Contenido::cargarRelaciones($this->id, get_class());
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO clase(idGrupo, titulo, objetivo) VALUES(:idGrupo, :titulo, :objetivo)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idGrupo", $this->idGrupo);
            $stmt->bindValue("titulo", $this->titulo);
            $stmt->bindValue("objetivo", $this->objetivo);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al crear la clase.";
            throw $th;
        }
    }

    public function actualizar() : void
    {
        $query = "UPDATE clase SET idGrupo = :idGrupo, titulo = :titulo, objetivo = :objetivo WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idGrupo", $this->idGrupo);
            $stmt->bindValue("titulo", $this->titulo);
            $stmt->bindValue("objetivo", $this->objetivo);
            $stmt->bindValue("id", $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                die($th->getMessage());
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar la clase.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        $errores = 0;
        if (empty($this->idGrupo)) {
            $_SESSION['errores'][] = "Debe seleccionar un grupo.";
            $errores++;
        }
        if (empty($this->titulo)) {
            $_SESSION['errores'][] = "No se puede dejar el titulo vacio.";
            $errores++;
        }
        if (!preg_match(REG_ALFANUMERICO, $this->titulo)) {
            $_SESSION['errores'][] = "El titulo solo puede contener caracteres alfanúmericos.";
            $errores++;
        }
        if (empty($this->objetivo)) {
            $_SESSION['errores'][] = "No se puede dejar el objetivo vacio.";
            $errores++;
        }
        if (!preg_match(REG_ALFANUMERICO, $this->objetivo)) {
            $_SESSION['errores'][] = "El objetivo solo puede contener caracteres alfanúmericos.";
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
    public function getIdGrupo() : int {
        return $this->idGrupo;
    }
    public function getTitulo() : string {
        return $this->titulo ?? "";
    }
    public function getObjetivo() : ?string {
        return $this->objetivo ?? null;
    }
}
?>