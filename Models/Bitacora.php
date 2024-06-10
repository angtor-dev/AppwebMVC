<?php
require_once "Models/Model.php";

class Bitacora extends Model
{
    public int $id;
    private ?int $idUsuario;
    private string $registro;
    private string $ruta;
    private string $fecha;

    public ?Usuario $usuario = null;

    function __construct()
    {
        parent::__construct();
        if (!empty($this->idUsuario)) {
            $this->usuario = Usuario::cargar($this->idUsuario);
        }
    }

    /**
     * Registra una accion en la bitacora
     * 
     * @param string $registro accion/actividad a registrar
     */
    public static function registrar(string $registro) : bool
    {
        global $requestUri;

        $db = Database::getInstance();
        $idUsuario = !empty($_SESSION['usuario']->id) ? $_SESSION['usuario']->id : "NULL";
        $ruta = $requestUri."/";

        $query = "INSERT INTO bitacora(idUsuario, registro, ruta)
            VALUES($idUsuario, '$registro', '$ruta')";

        $db->pdo()->query($query);

        return true;
    }

    // Override para impedir eliminar
    public function eliminar(bool $eliminadoLogico = true) : bool
    {
        $_SESSION['errores'][] = "No se puede eliminar un registro de la bitacora";
        return false;
    }

    public function listar_Bitacora(){
      
        try{

            $sql = "SELECT CONCAT(usuario.nombre, ' ' ,usuario.apellido, '-' ,usuario.cedula) AS usuarioDatos, bitacora.registro, bitacora.ruta, bitacora.fecha
            FROM bitacora INNER JOIN usuario ON usuario.id = bitacora.idUsuario  ORDER BY bitacora.fecha DESC";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;

        } catch (Exception $e) {
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
          
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }

    }

    // Getters
    public function getRegistro() : string {
        return $this->registro;
    }
    public function getRuta() : string {
        return $this->ruta;
    }
    public function getFecha() : string {
        return $this->fecha;
    }
}
?>