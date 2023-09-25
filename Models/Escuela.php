<?php
require_once "Models/Model.php";

class Escuela extends Model
{
    public int $id;
    private int $idSede;
    
    public Sede $sede;
    
    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idSede)) {
            $this->sede = Sede::cargar($this->idSede);
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO escuela(idSede) VALUES(:idSede)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idSede", $this->idSede);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al crear la escuela.";
            throw $th;
        }
    }
}
?>