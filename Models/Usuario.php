<?php
require_once "Models/Model.php";

class Usuario extends Model
{
    public int $id;
    public ?int $idCelulaFamiliar;
    public ?int $idCelulaCrecimiento;
    public ?int $idConsolidador;
    public string $cedula;
    public ?string $correo;
    public string $clave;
    public string $nombre;
    public string $apellido;
    public string $telefono;
    public string $direccion;
    public string $estadoCivil;
    public string $fechaNacimiento;
    public ?string $fechaConversion;
    public ?string $motivo;

    public function __construct()
    {
        parent::__construct();
    }

    public function getEdad() : int
    {
        if (empty($this->fechaNacimiento)) {
            return 0;
        }
        $nacimiento = new DateTime($this->fechaNacimiento);
        $edad = (new DateTime())->diff($nacimiento)->y;
        return $edad;
    }

    public function login(string $cedula, string $clave) : bool
    {
        if (empty($cedula) || empty($clave)) {
            return false;
        }

        $query = "SELECT * FROM usuario WHERE cedula = :cedula";
        
        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('cedula', $cedula);

            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            }

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!password_verify($clave, $usuario['clave'])) {
                return false;
            }

            foreach ($usuario as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }

            return true;
        } catch (\Throwable $th) {
            http_response_code(500);
            throw $th;
        }
        return true;
    }
}
?>