<?php
require_once "Models/Model.php";

class Clase extends Model
{
    public int $id;
    private int $idGrupo;
    private string $titulo;
    
    public Sede $grupo;
    
    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idGrupo)) {
            $this->grupo = Grupo::cargar($this->idGrupo);
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO clase(idGrupo, titulo) VALUES(:idGrupo, :titulo)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idGrupo", $this->idGrupo);
            $stmt->bindValue("titulo", $this->titulo);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al crear la clase.";
            throw $th;
        }
    }
}
?>