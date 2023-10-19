<?php
require_once "Models/Model.php";
require_once "Models/NivelCrecimiento.php";

class Subnivel extends Model
{
    public int $id;
    private int $idNivelCrecimiento;
    private string $nombre;
    private int $nivel;
    private int $estatus;

    public NivelCrecimiento $nivelCrecimiento;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idNivelCrecimiento)) {
            $this->nivelCrecimiento = NivelCrecimiento::cargar($this->idNivelCrecimiento);
        }
    }

    public function actualizar() : void
    {
        $query = "UPDATE subnivel SET nombre = :nombre, nivel = :nivel WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("nombre", $this->nombre);
            $stmt->bindValue("nivel", $this->nivel);
            $stmt->bindValue("id", $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                die($th->getMessage());
            }
            $_SESSION['errores'][] = "Ah ocurrido un error al actuizar el subnivel.";
            throw $th;
        }
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
    public function getNombre() : string {
        return $this->nombre ?? "";
    }
    public function getNivel() : int {
        return $this->nivel ?? 0;
    }
}
?>